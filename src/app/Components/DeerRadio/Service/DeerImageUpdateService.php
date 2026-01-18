<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\DeerRadio\Enum\DeerRadioPath;
use App\Components\ImageData\ImageData;
use App\Components\ImageData\ImageDataListProviderDriverRegistry;
use App\Components\Photoban\Service\PhotobanReadService;
use App\Components\Storage\Enum\StorageName;
use Exception;
use Illuminate\Filesystem\FilesystemManager;
use Intervention\Image\ImageManager;
use LogicException;
use Psr\Log\LoggerInterface;
use Throwable;

class DeerImageUpdateService
{
    public const DEER_IMAGE_PREFIX = 'deer_image_';

    /**
     * @param ImageDataListProviderDriverRegistry $imageDataListProviderDriverRegistry
     * @param FilesystemManager $filesystemManager
     * @param ImageManager $imageManager
     * @param PhotobanReadService $photobanReadService
     * @param DeerRadioDataAccessor $componentDataAccessor
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ImageDataListProviderDriverRegistry $imageDataListProviderDriverRegistry,
        private readonly FilesystemManager $filesystemManager,
        ImageManager $imageManager,
        private readonly PhotobanReadService $photobanReadService,
        private readonly DeerRadioDataAccessor $componentDataAccessor,
        private readonly LoggerInterface $logger
    )
    {
        $this->imageManagerLib = $imageManager;
    }

    /**
     * @return ImageData[]
     */
    private function requestImageDataList() : array {
        $imageDataList = [];

        foreach ($this->imageDataListProviderDriverRegistry->getDrivers() as $driver) {
            try {
                foreach ($driver->getImageDataList() as $imageData) {
                    $imageDataList[] = $imageData;
                }
            } catch (Throwable $throwable) {
                $this->logger->error(sprintf(
                    'Could not get deer image list with driver %s: %s',
                    $driver::class,
                    $throwable
                ));
            }
        }

        return $imageDataList;
    }

    /**
     * @param ImageData[] $imageDataList
     * @return ImageData|null
     */
    private function pickImageDataFromList(array $imageDataList) : ?ImageData {
        // let's filter images via amazing photoban feature
        $imageDataList = array_filter($imageDataList, function (ImageData $imageData) {
            $photobanUrl = $imageData->getPhotobanUrl();
            if ($photobanUrl !== null) {
                $photoban = $this->photobanReadService->findByUrl($imageData->getPhotobanUrl());
                if ($photoban !== null) {
                    $this->logger->info('Photoban triggered for: '.$photobanUrl);
                    return false;
                }
            }

            return true;
        });

        if (empty($imageDataList)) {
            return null;
        }

        return $imageDataList[array_rand($imageDataList)];
    }

    /**
     * @throws Exception
     */
    public function update(): void
    {
        $imageDataList = $this->requestImageDataList();
        $imageData = $this->pickImageDataFromList($imageDataList);
        if ($imageData === null) {
            throw new LogicException('Could not find any suitable deer photo');
        }

        $imagesDir = DeerRadioPath::DEER_IMAGES_DIR->value;
        // @todo check if filename is unique?
        $uniqueId = uniqid(self::DEER_IMAGE_PREFIX, true);

        $radioStorage = $this->filesystemManager->disk(StorageName::RADIO_STORAGE->value);
        $newImagePath = $radioStorage->path("$imagesDir/$uniqueId.jpg");
        if ($imageData->getIsRemote()) {
            $this->downloadRemoteImageTo($imageData, $newImagePath);
        } else {
            copy($imageData->getPath(), $newImagePath);
        }

        // we will store the local image data
        $localImageData = new ImageData($newImagePath, false)
            ->setPath($newImagePath)
            ->setImageUrl(strtok($imageData->getImageUrl() ?? '', '?') ?: '')
            ->setProfileUrl(strtok($imageData->getProfileUrl() ?? '', '?') ?: '')
            ->setAuthorName($imageData->getAuthorName() ?? '')
            ->setDescription(str_replace(["\r", "\n"], ['', ' '], $imageData->getDescription() ?? ''));

        $this->componentDataAccessor->setValue(DeerRadioDataKey::CURRENT_IMAGE_DATA->value, $localImageData);
    }

    /**
     * @param ImageData $imageData
     * @param string $downloadedFilePath
     * @return void
     * @throws Exception
     */
    private function downloadRemoteImageTo(ImageData $imageData, string $downloadedFilePath): void
    {
        $imagePath = $imageData->getPath();

        $opts = [
            'http' => [
                'timeout'  => '10',
            ],
        ];
        $context = stream_context_create($opts);
        $copyResult = copy($imagePath, $downloadedFilePath, $context);

        if (false === $copyResult) {
            throw new Exception(sprintf('Could not download/copy Deer Image from "%s"', $imagePath));
        }
    }
}

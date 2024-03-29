<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\DeerRadio\Enum\DeerRadioPath;
use App\Components\ImageData\ImageData;
use App\Components\ImageData\ImageDataListProviderDriverRegistry;
use App\Components\Photoban\Service\PhotobanReadService;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManager;
use LogicException;
use Psr\Log\LoggerInterface;
use Throwable;

class DeerImageUpdateService
{
    public const DEER_IMAGE_PREFIX = 'deer_image_'; // @todo move somewhere

    private ImageDataListProviderDriverRegistry $imageDataListProviderDriverRegistry;

    private Filesystem $radioStorage;

    private Filesystem $tempStorage;

    private ImageManager $imageManagerLib;

    private PhotobanReadService $photobanReadService;

    private LoggerInterface $logger;

    private DeerRadioDataAccessor $componentDataAccessor;

    /**
     * @param ImageDataListProviderDriverRegistry $imageDataListProviderDriverRegistry
     * @param Filesystem $radioStorage
     * @param Filesystem $tempStorage
     * @param ImageManager $imageManager
     * @param PhotobanReadService $photobanReadService
     * @param DeerRadioDataAccessor $componentDataAccessor
     * @param LoggerInterface $logger
     */
    public function __construct(
        ImageDataListProviderDriverRegistry $imageDataListProviderDriverRegistry,
        Filesystem $radioStorage,
        Filesystem $tempStorage,
        ImageManager $imageManager,
        PhotobanReadService $photobanReadService,
        DeerRadioDataAccessor $componentDataAccessor,
        LoggerInterface $logger
    )
    {
        $this->imageDataListProviderDriverRegistry = $imageDataListProviderDriverRegistry;
        $this->radioStorage = $radioStorage;
        $this->tempStorage = $tempStorage;
        $this->imageManagerLib = $imageManager;
        $this->photobanReadService = $photobanReadService;
        $this->componentDataAccessor = $componentDataAccessor;
        $this->logger = $logger;
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
        array_filter($imageDataList, function (ImageData $imageData) {
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

        $newImagePath = $this->radioStorage->path("$imagesDir/$uniqueId.jpg");
        if ($imageData->getIsRemote()) {
            $tempImagePath = $this->tempStorage->path("$uniqueId.tmp.jpg");
            $this->downloadRemoteImageTo($imageData, $newImagePath);
            $this->tempStorage->delete($tempImagePath);
        } else {
            $this->radioStorage->put($newImagePath, file_get_contents($imageData->getPath()));
        }

        // we will store the local image data
        $localImageData = (new ImageData($newImagePath, false))
            ->setPath($newImagePath)
            ->setImageUrl(strtok($imageData->getImageUrl() ?? '', '?'))
            ->setProfileUrl(strtok($imageData->getProfileUrl() ?? '', '?'))
            ->setAuthorName($imageData->getAuthorName() ?? '<unknown>')
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

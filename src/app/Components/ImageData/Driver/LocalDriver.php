<?php

declare(strict_types=1);

namespace App\Components\ImageData\Driver;

use App\Components\ImageData\ImageData;

class LocalDriver implements ImageDataListProviderInterface
{
    private array $imagePaths;

    public static function getName(): string
    {
        return 'local_image_list';
    }

    public function __construct(array $imagePaths = [])
    {
        $this->imagePaths = $imagePaths;
    }

    /**
     * @param array $imagePaths
     */
    public function setImagePaths(array $imagePaths): void
    {
        $this->imagePaths = $imagePaths;
    }

    /**
     * @inheritDoc
     * @return ImageData[]
     */
    public function getImageDataList(): array
    {
        $imageDataList = [];
        foreach ($this->imagePaths as $imagePath) {
            $imageDataList[] = $this->buildImageDataFromPath($imagePath);
        }

        return $imageDataList;
    }

    private function buildImageDataFromPath(string $imagePath) : ImageData
    {
        // @todo use more data
        return (new ImageData($imagePath, false))
            ->setImageUrl(null)
            ->setPhotobanUrl(null)
            ->setDescription(null)
            ->setAuthorName(null)
            ->setProfileUrl(null);
    }
}

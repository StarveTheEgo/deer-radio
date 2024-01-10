<?php

declare(strict_types=1);

namespace App\Components\ImageData;

use LogicException;

class UnsplashImageDataFactory
{
    /**
     * Builds image data from Unsplash API image info
     * @param array $unsplashImageInfo
     * @return ImageData
     */
    public function buildImageData(array $unsplashImageInfo) : ImageData {
        if (empty($unsplashImageInfo['urls']['raw'])) {
            throw new LogicException('Deer photo has no source URL');
        }

        $imageUrl = $unsplashImageInfo['links']['html'] ?? null;
        $photobanUrl = ($imageUrl !== null) ? strtok($imageUrl, '?') : null;

        return (new ImageData($unsplashImageInfo['urls']['raw'], true))
            ->setImageUrl($imageUrl)
            ->setPhotobanUrl($photobanUrl)
            ->setDescription($unsplashImageInfo['description'] ?? null)
            ->setAuthorName($unsplashImageInfo['user']['name'] ?? null)
            ->setProfileUrl($unsplashImageInfo['user']['links']['html'] ?? null);
    }
}

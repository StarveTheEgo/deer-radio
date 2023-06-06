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

        return (new ImageData($unsplashImageInfo['urls']['raw'], true))
            ->setImageUrl(strtok($unsplashImageInfo['links']['html'] ?? '', '?'))
            ->setDescription($unsplashImageInfo['description'] ?? '')
            ->setAuthorName($unsplashImageInfo['user']['name'] ?? '')
            ->setProfileUrl(strtok($unsplashImageInfo['user']['links']['html'] ?? '', '?'));
    }
}

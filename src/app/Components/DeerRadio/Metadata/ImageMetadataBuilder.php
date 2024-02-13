<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Metadata;

use App\Components\ImageData\ImageData;

class ImageMetadataBuilder
{
    public const IMAGE_DURATION = 5;

    public const IMAGE_DESCRIPTION_LIMIT = 60;

    /**
     * @param ImageData $imageData
     * @return array<string, scalar|null>
     */
    public function buildFromImageData(ImageData $imageData) : array
    {
        $description = $imageData->getDescription() ?: '';
        $description_length = mb_strlen($description);
        if ($description_length >= self::IMAGE_DESCRIPTION_LIMIT) {
            $description = mb_substr($description, 0, self::IMAGE_DESCRIPTION_LIMIT).'…';
        }

        return [
            'isRemote' => $imageData->getIsRemote(),
            'imageUrl' => $imageData->getImageUrl(),
            'photobanUrl' => $imageData->getPhotobanUrl(),
            'profileUrl' => $imageData->getProfileUrl(),
            'authorName' => $imageData->getAuthorName(),
            'description' => $description,
            'duration' => self::IMAGE_DURATION,
        ];
    }
}

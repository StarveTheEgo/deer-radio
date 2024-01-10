<?php

declare(strict_types=1);

namespace App\Components\ImageData\Driver;

use App\Components\ImageData\ImageData;

interface ImageDataListProviderInterface
{
    /**
     * Returns technical name of the driver.
     */
    public static function getName(): string;

    /**
     * Returns list of images data
     * @return ImageData[]
     */
    public function getImageDataList(): array;
}

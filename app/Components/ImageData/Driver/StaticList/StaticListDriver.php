<?php

declare(strict_types=1);

namespace App\Components\ImageData\Driver\StaticList;

use App\Components\ImageData\Driver\ImageDataListProviderInterface;
use App\Components\ImageData\ImageData;

class StaticListDriver implements ImageDataListProviderInterface
{
    public function __construct() {

    }

    public static function getName(): string
    {
        return 'static_list';
    }

    /**
     * @inheritDoc
     * @return ImageData[]
     */
    public function getImageDataList(): array
    {
        return [];
    }
}

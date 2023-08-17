<?php

declare(strict_types=1);

namespace App\Components\ImageData\Enum;

enum UnsplashDriverSettingKey: string
{
    case IS_ENABLED = 'unsplash-query.is_enabled';

    case DOWNLOAD_QUERY_PARAMS = 'unsplash-query.download_query_params';
}

<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient\Enum;

enum UnsplashClientSettingKey: string
{
    case APP_ID = 'unsplash-client.app_id';
    case APP_NAME = 'unsplash-client.app_name';
    case APP_SECRET = 'unsplash-client.app_secret';
}

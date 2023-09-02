<?php

declare(strict_types=1);

namespace App\Components\Google\Enum;

enum GoogleOutputSettingKey: string
{
    case ENDPOINT = 'google-output.endpoint';

    case API_KEY = 'google-output.api_key';

    case ACCESS_TOKEN = 'google-output.access_token';

    case REFRESH_TOKEN = 'google-output.refresh_token';
    case AUTH_CONFIG = 'google-output.auth_config';
}

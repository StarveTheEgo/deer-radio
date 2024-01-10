<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Enum;

enum LivestreamSettingKey: string
{
    // @todo add settings via seeders
    case ENDPOINT = 'live-stream.endpoint';

    case API_KEY = 'live-stream.api_key';
}

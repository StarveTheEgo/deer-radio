<?php

declare(strict_types=1);

namespace App\Components\Liquidsoap\Enum;

enum LiquidsoapSettingKey: string
{
    case MAX_INACTIVE_STREAM_DURATION = 'liquidsoap.max_inactive_stream_duration';
}

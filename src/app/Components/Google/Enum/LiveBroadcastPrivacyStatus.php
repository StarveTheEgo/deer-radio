<?php

declare(strict_types=1);

namespace App\Components\Google\Enum;

enum LiveBroadcastPrivacyStatus: string
{
    case PUBLIC = 'public';

    case PRIVATE = 'private';

    case UNLISTED = 'unlisted';
}

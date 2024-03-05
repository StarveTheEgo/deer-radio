<?php

declare(strict_types=1);

namespace App\Components\Output\Enum;

enum OutputStreamState : string
{
    case NOT_READY = 'not_ready';

    case UNKNOWN = 'unknown';

    case CREATED = 'created';

    case LIVE = 'live';

    case FINISHED = 'finished';
}

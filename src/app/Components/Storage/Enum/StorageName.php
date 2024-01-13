<?php

declare(strict_types=1);

namespace App\Components\Storage\Enum;

enum StorageName: string
{
    case PUBLIC_STORAGE = 'public';

    case RADIO_STORAGE = 'radio-storage';

    case TEMP_STORAGE = 'temp';

    case PRIVATE_STORAGE = 'private';
}

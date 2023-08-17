<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Enum;

enum DeerRadioDataKey: string
{
    case CURRENT_SONG_ID = 'current_song_id';

    case NEXT_SONG_ID = 'next_song_id';

    case CURRENT_IMAGE_DATA = 'current_image_data';
}

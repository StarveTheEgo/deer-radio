<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Enum;

enum SongManagerSettingKey: string
{
    case IS_ENABLED = 'song_manager.is_enabled';

    case LEAST_PLAYED_COUNT_PERCENTAGE = 'song_manager.least_played_count_percentage';

    case SONG_INTERVAL = 'song_manager.song_interval';

    case AUTHOR_INTERVAL = 'song_manager.author_interval';
}

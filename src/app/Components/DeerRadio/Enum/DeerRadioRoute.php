<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Enum;

enum DeerRadioRoute: string
{
    case PING = 'deer-radio.ping';

    case DOWNLOAD_SONG = 'deer-radio.song.download';
}

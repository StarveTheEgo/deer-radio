<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Enum;

enum OauthTokenType: string
{
    case BEARER = 'Bearer';
}

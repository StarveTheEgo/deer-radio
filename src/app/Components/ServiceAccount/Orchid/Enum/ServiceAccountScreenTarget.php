<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Orchid\Enum;

enum ServiceAccountScreenTarget: string
{
    case ACCOUNTS_LIST = 'service-accounts';

    case CURRENT_ACCOUNT = 'service-account';
}

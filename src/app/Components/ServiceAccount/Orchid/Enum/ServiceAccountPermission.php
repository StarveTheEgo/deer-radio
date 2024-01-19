<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Orchid\Enum;

enum ServiceAccountPermission: string
{
    case CAN_VIEW = 'platform.app.output.can_view';

    case CAN_EDIT = 'platform.app.output.can_create';
}

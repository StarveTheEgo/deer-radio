<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Enum;

enum OutputRoute: string
{
    case INDEX = 'platform.app.output';

    case CREATE = 'platform.app.output.create';

    case EDIT = 'platform.app.output.edit';
}

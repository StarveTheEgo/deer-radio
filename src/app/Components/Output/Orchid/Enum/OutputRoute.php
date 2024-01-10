<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Enum;

enum OutputRoute: string
{
    case CREATE = 'platform.app.output.create';

    case INDEX = 'platform.app.output';

    case EDIT = 'platform.app.output.edit';
}

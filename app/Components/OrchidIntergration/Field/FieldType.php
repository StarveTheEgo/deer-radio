<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field;

enum FieldType: string
{
    case INPUT = 'input';
    case TOGGLE = 'toggle';

    public function title(): string
    {
        return match ($this) {
            self::INPUT => 'Input field',
            self::TOGGLE => 'Toggle field',
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field;

enum FieldType: string
{
    case INPUT = 'input';
    case TOGGLE = 'toggle';

    case CODE = 'code';

    public function title(): string
    {
        return match ($this) {
            self::INPUT => 'Input field',
            self::TOGGLE => 'Toggle field',
            self::CODE => 'Code field',
        };
    }
}

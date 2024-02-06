<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Enum;

enum FieldType: string
{
    case INPUT = 'input';

    case TEXTAREA = 'textarea';

    case TOGGLE = 'toggle';

    case CODE = 'code';

    public function title(): string
    {
        return match ($this) {
            self::INPUT => 'Input field',
            self::TEXTAREA => 'Text area',
            self::TOGGLE => 'Toggle field',
            self::CODE => 'Code field',
        };
    }
}

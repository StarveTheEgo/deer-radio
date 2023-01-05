<?php

namespace App\Components\Setting\Orchid\Field;

enum FieldType: string
{
    case INPUT = 'input';

    public function title(): string
    {
        return match ($this) {
            self::INPUT => 'Input field',
        };
    }
}

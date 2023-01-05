<?php

namespace App\Components\Setting\Orchid\Field;

use Orchid\Screen\Field;

interface FieldFactoryInterface
{
    public static function getType(): FieldType;

    public function buildField(FieldOptions $options): Field;
}

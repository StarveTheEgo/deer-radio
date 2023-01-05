<?php

namespace App\Components\Setting\Orchid\Field\Factory\Input;

use App\Components\Setting\Orchid\Field\FieldFactoryInterface;
use App\Components\Setting\Orchid\Field\FieldOptions;
use App\Components\Setting\Orchid\Field\FieldType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class InputFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::INPUT;
    }

    public function buildField(FieldOptions $options): Field
    {
        $customOptions = InputCustomOptions::fromArray($options->getCustom() ?? []);

        return Input::make()
            ->type($customOptions->getType())
            ->title(__($options->getTitle() ?? ''));
    }
}

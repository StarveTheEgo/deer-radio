<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Factory\Input;

use App\Components\OrchidIntergration\Field\FieldFactoryInterface;
use App\Components\OrchidIntergration\Field\FieldOptions;
use App\Components\OrchidIntergration\Field\FieldType;
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

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Factory\Toggle;

use App\Components\OrchidIntergration\Field\FieldFactoryInterface;
use App\Components\OrchidIntergration\Field\FieldOptions;
use App\Components\OrchidIntergration\Field\FieldType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Switcher;

class ToggleFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::TOGGLE;
    }

    public function buildField(FieldOptions $options): Field
    {
        $customOptions = ToggleCustomOptions::fromArray($options->getCustom() ?? []);

        return Switcher::make()
            ->title(__($options->getTitle() ?? ''))
            ->help(__($customOptions->getDescription() ?? ''))
            ->sendTrueOrFalse();
    }
}

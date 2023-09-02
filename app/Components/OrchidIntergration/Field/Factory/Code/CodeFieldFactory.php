<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Factory\Code;

use App\Components\OrchidIntergration\Field\FieldFactoryInterface;
use App\Components\OrchidIntergration\Field\FieldOptions;
use App\Components\OrchidIntergration\Field\FieldType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Code;

class CodeFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::CODE;
    }

    public function buildField(FieldOptions $options): Field
    {
        $customOptions = CodeCustomOptions::fromArray($options->getCustom() ?? []);

        return Code::make()
            ->language($customOptions->getLanguage())
            ->title(__($options->getTitle() ?? ''));
    }
}

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Code\Factory;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Code\FieldOptions\CodeOptions;
use App\Components\OrchidIntergration\Interface\FieldFactoryInterface;
use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Code;
use Webmozart\Assert\Assert;

class CodeFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::CODE;
    }

    /**
     * @param FieldOptionsInterface&CodeOptions $options
     * @return Field
     */
    public function buildField(FieldOptionsInterface $options): Field
    {
        Assert::isInstanceOf($options, CodeOptions::class);

        return Code::make()
            ->language($options->getLanguage())
            ->title(__($options->getTitle()));
    }
}

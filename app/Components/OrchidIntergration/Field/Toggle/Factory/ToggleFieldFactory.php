<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Toggle\Factory;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Toggle\FieldOptions\ToggleOptions;
use App\Components\OrchidIntergration\Interface\FieldFactoryInterface;
use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Switcher;
use Webmozart\Assert\Assert;

class ToggleFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::TOGGLE;
    }

    /**
     * @param FieldOptionsInterface&ToggleOptions $options
     * @return Field
     */
    public function buildField(FieldOptionsInterface $options): Field
    {
        Assert::isInstanceOf($options, ToggleOptions::class);

        return Switcher::make()
            ->title(__($options->getTitle() ?? ''))
            ->help(__($options->getDescription() ?? ''))
            ->sendTrueOrFalse();
    }
}

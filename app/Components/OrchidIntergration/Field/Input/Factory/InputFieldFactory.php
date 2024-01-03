<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Input\Factory;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Input\FieldOptions\InputOptions;
use App\Components\OrchidIntergration\Interface\FieldFactoryInterface;
use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Webmozart\Assert\Assert;

class InputFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::INPUT;
    }

    /**
     * @param FieldOptionsInterface&InputOptions $options
     * @return Input
     */
    public function buildField(FieldOptionsInterface $options): Input
    {
        Assert::isInstanceOf($options, InputOptions::class);

        return Input::make()
            ->type($options->getType())
            // @todo description
            ->title(__($options->getTitle() ?? ''));
    }
}

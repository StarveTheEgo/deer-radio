<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Textarea\Factory;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Textarea\FieldOptions\TextAreaOptions;
use App\Components\OrchidIntergration\Interface\FieldFactoryInterface;
use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use Orchid\Screen\Fields\TextArea;
use Webmozart\Assert\Assert;

class TextAreaFieldFactory implements FieldFactoryInterface
{
    public static function getType(): FieldType
    {
        return FieldType::TEXTAREA;
    }

    /**
     * @param FieldOptionsInterface&TextAreaOptions $options
     * @return TextArea
     */
    public function buildField(FieldOptionsInterface $options): TextArea
    {
        Assert::isInstanceOf($options, TextAreaOptions::class);

        return TextArea::make()
            ->title(__($options->getTitle() ?? ''));
    }
}

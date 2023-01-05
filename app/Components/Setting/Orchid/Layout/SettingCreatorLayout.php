<?php

declare(strict_types=1);

namespace App\Components\Setting\Orchid\Layout;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class SettingCreatorLayout extends Rows
{
    private string $prefix;
    private Field $valueEditField;

    public function __construct(string $prefix, Field $valueEditField)
    {
        $this->prefix = $prefix;
        $this->valueEditField = $valueEditField;
    }

    public function fields(): array
    {
        return [
            $this->buildNameInput(),
            $this->buildDescriptionInput(),
            $this->valueEditField
                ->set('name', $this->buildFullFieldName('value'))
                ->set('title', __('Value')),
        ];
    }

    private function buildNameInput(): Input
    {
        return Input::make($this->buildFullFieldName('key'))
            ->type('text')
            ->max(64)
            ->required()
            ->title(__('Name'));
    }

    private function buildDescriptionInput(): TextArea
    {
        return TextArea::make($this->buildFullFieldName('value'))
            ->title(__('Description'));
    }

    private function buildFullFieldName(string $shortName): string
    {
        return $this->prefix.'.'.$shortName;
    }
}

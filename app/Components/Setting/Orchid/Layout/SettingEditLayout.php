<?php

declare(strict_types=1);

namespace App\Components\Setting\Orchid\Layout;

use App\Components\OrchidIntergration\Field\FieldFactoryRegistry;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class SettingEditLayout extends Rows
{
    private FieldFactoryRegistry $fieldFactoryRegistry;

    /**
     * @param FieldFactoryRegistry $fieldFactoryRegistry
     */
    public function __construct(FieldFactoryRegistry $fieldFactoryRegistry)
    {
        $this->fieldFactoryRegistry = $fieldFactoryRegistry;
    }

    protected function fields(): iterable
    {
        return [
            Input::make('key')
                ->title('Setting key')
                ->placeholder('group-name.setting_name')
                ->help('Unique key of the setting. Must have format <code>group-name.setting_name</code>')
                ->required(),

            Input::make('description')
                ->title('Description')
                ->placeholder('Some incredible description'),

            Select::make('fieldType')
                ->title('Field type')
                ->options($this->fieldFactoryRegistry->getTypeTitles())
                ->required(),

            Code::make('fieldOptions')
                ->title('Field options')
                ->language(Code::JS)
                ->value("{\n    \n}")
                ->required(),

            Input::make('value')
                ->title('Initial value'),

            CheckBox::make('isEncrypted')
                ->title('Keep the value encrypted')
                ->sendTrueOrFalse(),
        ];
    }
}

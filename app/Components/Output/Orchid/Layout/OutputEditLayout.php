<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Layout;

use App\Components\OrchidIntergration\Factory\JsonFieldFactory;
use App\Components\OrchidIntergration\Helper\PrefixHelper;
use App\Components\Output\Orchid\Screen\OutputEditScreen;
use App\Components\Output\Registry\OutputDriverRegistry;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;

class OutputEditLayout extends Rows
{
    /** @var string Prefix for every field's name of this layout */
    private const FIELD_PREFIX = OutputEditScreen::QUERY_KEY_OUTPUT;

    private OutputDriverRegistry $driverRegistry;

    /**
     * @param OutputDriverRegistry $driverRegistry
     */
    public function __construct(OutputDriverRegistry $driverRegistry)
    {
        $this->driverRegistry = $driverRegistry;
    }

    /**
     * @return iterable
     */
    protected function fields(): iterable
    {
        $fields = [
            Input::make('id')
                ->type('hidden')
                ->required(),
        ];

        if ($this->query[OutputEditScreen::QUERY_KEY_OUTPUT] !== null) {
            // read-only driver name in case of editing existing Output
            $fields = array_merge($fields, [
                Label::make('driverName'),

                Input::make('driverName')
                    ->type('hidden')
                    ->required()
            ]);
        } else {
            // selectable driver name for creating new Output
            $fields[] = Select::make('driverName')
                ->title('Output type')
                ->options($this->driverRegistry->getDriverTitles())
                ->required();
        }

        $fields = array_merge($fields, [
            Input::make('outputName')
                ->title(__('Output name'))
                ->required(),

            JsonFieldFactory::make('driverConfig')
                ->title('Driver config')
                ->language(Code::JS)
                ->value("{\n    \n}")
                ->required(),

            Switcher::make('isActive')
                ->sendTrueOrFalse()
                ->title(__('Is active'))
                ->help(__('This output will be used in the livestream')),
        ]);

        return PrefixHelper::addPrefixToFields(self::FIELD_PREFIX.'.', $fields);
    }
}

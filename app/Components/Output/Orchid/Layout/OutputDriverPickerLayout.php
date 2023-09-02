<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Layout;

use App\Components\Output\OutputDriverRegistry;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class OutputDriverPickerLayout extends Rows
{
    private OutputDriverRegistry $driverRegistry;

    public function __construct(OutputDriverRegistry $driverRegistry)
    {
        $this->driverRegistry = $driverRegistry;
    }

    /**
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            $this->createDriverSelect(),
        ];
    }

    private function createDriverSelect() : Select
    {
        return Select::make('driver')
            ->title('Output type')
            ->options($this->driverRegistry->getDriverTitles())
            ->required();
    }
}

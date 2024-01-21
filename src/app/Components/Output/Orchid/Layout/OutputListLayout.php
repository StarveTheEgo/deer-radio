<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Layout;

use App\Components\Output\Entity\Output;
use App\Components\Output\Enum\OutputRoute;
use App\Components\Output\Orchid\Screen\OutputIndexScreen;
use App\Components\Output\Registry\OutputDriverRegistry;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class OutputListLayout extends Table
{
    /**
     * @var string
     */
    public $target = OutputIndexScreen::QUERY_KEY_OUTPUTS;

    private OutputDriverRegistry $outputDriverRegistry;

    /**
     * @param OutputDriverRegistry $outputDriverRegistry
     */
    public function __construct(OutputDriverRegistry $outputDriverRegistry)
    {
        $this->outputDriverRegistry = $outputDriverRegistry;
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('outputName', __('Output name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('driverName', __('Driver'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Output $output) {
                    $driverName = $output->getDriverName();
                    $driverClass = $this->outputDriverRegistry->fetchDriverClassByName($driverName);
                    if ($driverClass === null) {
                        Toast::error(__('Invalid driver name: :driverName', ['driverName' => $driverName]));
                        return '[error]';
                    }
                    return $driverClass::getTitle();
                }),

            TD::make('isActive', __('Is active'))
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Output $output) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route(OutputRoute::EDIT->value, [
                                    'output' => $output->getId(),
                                ])
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Are you sure you want to delete this output?'))
                                ->method('delete', [
                                    'output' => $output->getId(),
                                ]),
                        ]);
                }),
        ];
    }
}

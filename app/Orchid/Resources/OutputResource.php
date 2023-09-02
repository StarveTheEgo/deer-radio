<?php

declare(strict_types=1);

namespace App\Orchid\Resources;

use App\Components\Output\Orchid\Action\CreateOutputAction;
use App\Components\Output\Orchid\Layout\OutputDriverPickerLayout;
use App\Components\Output\OutputDriverRegistry;
use App\Models\Output;
use App\Components\Output\Entity\Output as DoctrineOutput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class OutputResource extends AbstractResource
{
    const MODAL_CREATE_OUTPUT = 'createOutput';

    public static $model = Output::class;

    private OutputDriverRegistry $driverRegistry;

    public function __construct(OutputDriverRegistry $driverRegistry)
    {
        $this->driverRegistry = $driverRegistry;
    }

    public static function icon(): string
    {
        return 'feed';
    }

    public static function description(): ?string
    {
//        Layout::modal(self::MODAL_CREATE_OUTPUT, [
//            OutputDriverPickerLayout::class,
//        ]),
        return __('Livestream outputs');
    }

    public function actions(): array
    {
        return [
            new CreateOutputAction(self::MODAL_CREATE_OUTPUT),
        ];
    }

    public function fields(): array
    {
        // request::get xdd
        return [
            Input::make('output_name')
                ->title(__('Output name'))
                ->required(),

//            Select::make('driver_name')
//                ->title(__('Output type'))
//                ->options($this->driverRegistry->getDriverTitles()),

            Code::make('driver_config')
                ->title('Driver config')
                ->language(Code::JS)
                ->required(),

            Switcher::make('is_active')
                ->sendTrueOrFalse()
                ->title(__('Is active'))
                ->help(__('This output will be used in the livestream')),
        ];
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        $driverTitles = $this->driverRegistry->getDriverTitles();

        return [
            TD::make('id', __('ID'))
                ->sort()
                ->filter(Input::make()),

            TD::make('output_name', __('Output name'))
                ->sort()
                ->filter(Input::make()),

            TD::make('driver_name', __('Driver name'))
                ->sort()
                ->filter(Input::make())
                ->render(function(Output $output) use ($driverTitles) {
                    $driverName = $output->driver_name;

                    return $driverTitles[$driverName] ?? $driverName;
                }),

            TD::make('is_active', __('Is active'))
                ->sort(),
        ];
    }

    /**
     * @return Sight[]
     */
    public function legend(): array
    {
        $driverTitles = $this->driverRegistry->getDriverTitles();

        return [
            Sight::make('id', __('ID')),

            Sight::make('output_name', __('Output name')),

            Sight::make('driver_name', __('Driver name'))
                ->render(function(Output $output) use ($driverTitles) {
                    $driverName = $output->driver_name;

                    return $driverTitles[$driverName] ?? $driverName;
                }),

            Sight::make('is_active', __('Is active')),

            Sight::make('created_at', __('Created at')),

            Sight::make('updated_at', __('Updated at')),
        ];
    }

    /**
     * @param Model|Output $model
     * @return array
     */
    public function rules(Model $model): array
    {
        $model_input = request()->input('model');

        return [
            'output_name' => [
                'required',
                'string',
                Rule::unique(DoctrineOutput::class, 'outputName')
                    ->where('driverName', $model_input['driver_name'])
                    ->ignore($model->id),
            ],

            'driver_name' => [
                'required',
                'string',
            ],

            'driver_config' => [
                'required',
                'string',
                'json',
            ],

            'is_active' => [
                'boolean',
            ],
        ];
    }
}

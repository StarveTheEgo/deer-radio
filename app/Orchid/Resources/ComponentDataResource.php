<?php

declare(strict_types=1);

namespace App\Orchid\Resources;

use App\Models\ComponentData;
use Illuminate\Support\Str;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class ComponentDataResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ComponentData::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'Components data';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Component data';
    }

    public static function icon(): string
    {
        return 'wrench';
    }

    public static function description(): ?string
    {
        return __('Internal components data, do not if not sure! :-D');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('component')
                ->title(__('Component name'))
                ->required(),

            Input::make('field')
                ->title(__('Field'))
                ->required(),

            Input::make('value')
                ->title(__('Value')),
        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),

            TD::make('component', __('Component name'))
                ->sort()
                ->filter(Input::make()),

            TD::make('field', __('Field'))
                ->sort()
                ->filter(Input::make()),

            TD::make('value', __('Value')),

            TD::make('created_at', 'Date of creation')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),

            TD::make('updated_at', 'Update date')
                ->render(function ($model) {
                    return $model->updated_at?->toDateTimeString();
                }),
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }
}

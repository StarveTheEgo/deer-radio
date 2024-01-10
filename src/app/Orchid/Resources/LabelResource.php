<?php

namespace App\Orchid\Resources;

use App\Components\Label\Entity\Label as DoctrineLabel;
use App\Models\Label;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class LabelResource extends AbstractResource
{
    public static $model = Label::class;

    public static function icon(): string
    {
        return 'organization'; // original icon: friends
    }

    public static function description(): ?string
    {
        return __('Music labels');
    }

    public function fields(): array
    {
        return [
            Input::make('name')
                ->title(__('Name'))
                ->required(),
        ];
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->filter(Input::make()),

            TD::make('name', __('Name'))
                ->sort()
                ->filter(Input::make()),
        ];
    }

    /**
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id', __('ID')),

            Sight::make('name', __('Name')),

            Sight::make('created_at', __('Date of creation')),

            Sight::make('updated_at', __('Update date')),

            Sight::make('label_links', __('Links'))
                ->render(function (Label $label) {
                    return view('label/label-links', ['label_links' => $label->labelLinks()->get()]);
                }),
        ];
    }

    /**
     * @param Model|Label $model
     * @return array[]
     */
    public function rules(Model $model): array
    {
        return [
            'name' => [
                'required',
                Rule::unique(DoctrineLabel::class, 'name')->ignore($model->id),
            ],
        ];
    }

    public function filters(): array
    {
        return [];
    }
}

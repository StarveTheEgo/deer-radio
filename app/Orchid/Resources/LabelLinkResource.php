<?php

namespace App\Orchid\Resources;

use App\Models\Label;
use App\Models\LabelLink;
use App\Orchid\Filters\RelatedFieldStringFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class LabelLinkResource extends AbstractResource
{
    public static $model = LabelLink::class;

    public static function icon(): string
    {
        return 'list';
    }

    public static function description(): ?string
    {
        return 'Label links (social pages, music stores)';
    }

    public function with(): array
    {
        return ['label'];
    }

    public function fields(): array
    {
        return [
            Relation::make('label_id')
                ->fromModel(Label::class, 'name')
                ->title('Label')
                ->required(),

            Input::make('url')
                ->type('url')
                ->title('URL')
                ->help('URL to the social page or store or deers :-)')
                ->required(),
        ];
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),

            TD::make('label_id', __('Label'))
                ->render(function (LabelLink $label_link) {
                    return $label_link->label->name;
                }),

            TD::make('url', __('URL'))
                ->sort(),
        ];
    }

    /**
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id', __('ID')),

            Sight::make('label_id', __('Label'))
                ->render(function (LabelLink $label_link) {
                    return $label_link->label->name;
                }),

            Sight::make('url', __('URL')),

            Sight::make('created_at', __('Date of creation')),

            Sight::make('updated_at', __('Update date')),
        ];
    }

    public function rules(Model $model): array
    {
        $model_input = request()->input('model');

        return [
            'label_id' => [
                'required',
                'exists:'.Label::class.',id',
            ],
            'url' => [
                'required',
                'url',
                Rule::unique(self::$model, 'url')
                    ->where(function ($query) use ($model_input) {
                        return $query
                            ->where('label_id', $model_input['label_id'])
                            ->where('url', $model_input['url']);
                    })->ignore($model),
            ],
        ];
    }

    public function filters(): array
    {
        return [
            new RelatedFieldStringFilter(__('Label'), 'label', 'name'),
        ];
    }
}

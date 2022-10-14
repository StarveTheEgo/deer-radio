<?php

namespace App\Orchid\Resources;

use App\Models\Album;
use App\Models\Author;
use App\Orchid\Filters\RelatedFieldStringFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class AlbumResource extends AbstractResource
{
    public static $model = Album::class;

    public static function icon(): string
    {
        return 'playlist';
    }

    public static function description(): ?string
    {
        return 'Music albums';
    }

    public function fields(): array
    {
        return [
            Relation::make('author_id')
                ->fromModel(Author::class, 'name')
                ->title(__('Author'))
                ->required(),

            Input::make('title')
                ->type('text')
                ->title(__('Album title'))
                ->required(),

            Input::make('year')
                ->type('number')
                ->title(__('Album release year'))
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

            TD::make('author_id', 'Author')
                ->render(function (Album $album) {
                    return $album->author->name;
                }),

            TD::make('title', __('Title'))
                ->sort()
                ->filter(Input::make()),

            TD::make('year', __('Year'))
                ->sort()
                ->filter(Input::make()),

            TD::make('created_at', __('Date of creation'))
                ->render(function (Album $album) {
                    return $album->created_at?->toDateTimeString();
                }),

            TD::make('updated_at', __('Update date'))
                ->render(function (Album $album) {
                    return $album->updated_at?->toDateTimeString();
                }),
        ];
    }

    /**
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id', __('ID')),

            Sight::make('author_id', __('Author'))
                ->render(function (Album $album) {
                    return $album->author->name;
                }),

            Sight::make('title', __('Title')),

            Sight::make('year', __('Year')),

            Sight::make('created_at', __('Date of creation')),

            Sight::make('updated_at', __('Update date')),
        ];
    }

    public function rules(Model $model): array
    {
        $model_input = request()->input('model');

        return [
            'author_id' => [
                'required',
                'exists:'.Author::class.',id',
            ],
            'title' => [
                'required',
                Rule::unique(self::$model, 'title')
                    ->where(function ($query) use ($model_input) {
                        return $query
                            ->where('author_id', $model_input['author_id'])
                            ->where('title', $model_input['title']);
                    })->ignore($model),
            ],
            'year' => [
                'required',
                'integer',
                'min:1500',
                'max:'.date('Y'),
            ],
        ];
    }

    public function filters(): array
    {
        return [
            new RelatedFieldStringFilter(__('Author'), 'author', 'name'),
        ];
    }
}

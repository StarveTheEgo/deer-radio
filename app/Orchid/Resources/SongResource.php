<?php

namespace App\Orchid\Resources;

use App\Models\Album;
use App\Models\Author;
use App\Models\Label;
use App\Models\Song;
use App\Orchid\Filters\RelatedAlbumFilter;
use App\Orchid\Filters\RelatedAuthorFilter;
use App\Orchid\Filters\RelatedLabelFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class SongResource extends AbstractResource
{
    public static $model = Song::class;

    public static function icon(): string
    {
        return 'music-tone-alt';
    }

    public static function description(): ?string
    {
        return __('All songs we know :-)');
    }

    public function with(): array
    {
        return ['author', 'album', 'label'];
    }

    public function fields(): array
    {
        return [
            Input::make('title')
                ->title(__('Title'))
                ->required(),

            Relation::make('author_id')
                ->fromModel(Author::class, 'name')
                ->title('Author')
                ->required(),

            Relation::make('album_id')
                ->fromModel(Album::class, 'title')
                ->title('Album'),

            Relation::make('label_id')
                ->fromModel(Label::class, 'name')
                ->title('Label'),

            Input::make('year')
                ->type('number')
                ->title(__('Album release year'))
                ->required(),

            // @todo
            Input::make('source')
                ->title(__('Source'))
                ->required(),

            Input::make('tempo')
                ->type('number')
                ->title(__('Tempo'))
                ->required(),

            Switcher::make('is_active')
                ->sendTrueOrFalse()
                ->title(__('Is active'))
                ->help(__('The song can be rotated')),

            Input::make('volume')
                ->type('number')
                ->title(__('Volume'))
                ->required(),

            Input::make('unsplash_search_query')
                ->type('text')
                ->title(__('Unsplash search query'))
                ->help(__('Special search query for photos. Leave blank to have default search query')),
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

            TD::make('title', __('Title'))
                ->sort()
                ->filter(Input::make()),

            TD::make('author_id', __('Author'))
                ->render(function (Song $song) {
                    return $song->author->name;
                }),

            TD::make('album_id', __('Album'))
                ->render(function (Song $song) {
                    return $song->album?->title;
                }),

            TD::make('label_id', __('Label'))
                ->render(function (Song $song) {
                    return $song->label?->name;
                }),

            TD::make('year', __('Year'))
                ->sort()
                ->filter(Input::make()),

            // @todo
            TD::make('source', __('Source'))
                ->render(function () {
                    return '-';
                })
                ->filter(Input::make()),

            TD::make('tempo', __('Tempo')),

            TD::make('is_active', __('Is active'))
                ->sort(),

            TD::make('volume', __('Volume')),

            TD::make('played_at', __('Played at'))
                ->render(function ($model) {
                    return $model->played_at?->toDateTimeString();
                }),

            TD::make('finished_at', __('Finished at'))
                ->render(function ($model) {
                    return $model->finished_at?->toDateTimeString();
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

            Sight::make('title', __('Title')),

            Sight::make('author_id', __('Author')),

            Sight::make('album_id', __('Album')),

            Sight::make('label_id', __('Label')),

            Sight::make('year', __('Year')),

            Sight::make('source', __('Source')),

            Sight::make('tempo', __('Tempo')),

            Sight::make('played_at', __('Played at')),

            Sight::make('finished_at', __('Finished at')),

            Sight::make('is_active', __('Is active')),

            Sight::make('volume', __('Volume')),

            Sight::make('created_at', __('Created at')),

            Sight::make('updated_at', __('Updated at')),
        ];
    }

    public function rules(Model $model): array
    {
        $model_input = request()->input('model');

        return [
            'title' => [
                'required',
                Rule::unique(self::$model, 'title')
                    ->where(function ($query) use ($model_input) {
                        return $query
                            ->where('author_id', $model_input['author_id'])
                            ->where('album_id', $model_input['album_id'])
                            ->where('label_id', $model_input['label_id']);
                    })->ignore($model),
            ],

            'author_id' => [
                'required',
                'exists:'.Author::class.',id',
            ],

            'album_id' => [
                'exists:'.Album::class.',id',
            ],

            'label_id' => [
                'exists:'.Label::class.',id',
            ],

            'year' => [
                'required',
                'integer',
                'min:1500',
                'max:'.date('Y'),
            ],

            'source' => [
                'required',
            ],

            'tempo' => [
                'required',
                'integer',
                'between:0,10',
            ],

            'is_active' => [
                'boolean',
            ],

            'volume' => [
                'required',
                'integer',
                'between:1,100',
            ],

            'unsplash_search_query' => [],
        ];
    }

    public function filters(): array
    {
        return [
            new RelatedAuthorFilter(),
            new RelatedAlbumFilter(),
            new RelatedLabelFilter(),
        ];
    }

}

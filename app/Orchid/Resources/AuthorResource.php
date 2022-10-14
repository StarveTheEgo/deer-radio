<?php

namespace App\Orchid\Resources;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class AuthorResource extends AbstractResource
{
    public static $model = Author::class;

    public static function icon(): string
    {
        return 'user';
    }

    public static function description(): ?string
    {
        return __('All music authors we know :-)');
    }

    public function fields(): array
    {
        return [
            Input::make('name')
                ->title(__('Name'))
                ->help(__('Wow! Who is this amazing music maker this time?'))
                ->required(),

            Switcher::make('is_active')
                ->sendTrueOrFalse()
                ->title(__('Is active'))
                ->help(__('Author\'s music can be rotated')),

            Input::make('unsplash_search_query')
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

            TD::make('name', __('Name'))
                ->sort()
                ->filter(Input::make()),

            TD::make('unsplash_search_query', __('Unsplash search query'))
                ->sort()
                ->filter(Input::make()),

            TD::make('played_count', __('Played count'))
                ->sort(),

            TD::make('played_at', __('Played at'))
                ->sort(),

            TD::make('finished_at', __('Finished at'))
                ->sort(),

            TD::make('is_active', __('Is active'))
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

            Sight::make('name', __('Name')),

            Sight::make('unsplash_search_query', __('Unsplash search query')),

            Sight::make('played_count', __('Played count')),

            Sight::make('played_at', __('Played at')),

            Sight::make('finished_at', __('Finished at')),

            Sight::make('is_active', __('Is active')),

            Sight::make('created_at', __('Date of creation')),

            Sight::make('updated_at', __('Update date')),

            Sight::make('author_links', __('Links'))
                ->render(function (Author $author) {
                    return view('author/author-links', ['author_links' => $author->authorLinks()->get()]);
                }),
        ];
    }

    public function rules(Model $model): array
    {
        return [
            'name' => [
                'required',
                Rule::unique(self::$model, 'name')->ignore($model),
            ],
            'is_active' => [
                'boolean',
            ],
            'unsplash_search_query' => [
            ],
        ];
    }

    public function filters(): array
    {
        return [];
    }
}

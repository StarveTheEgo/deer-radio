<?php

namespace App\Orchid\Layouts\Author;

use App\Models\Author;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuthorListLayout extends Table
{

    public $target = 'authors';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->filter(Input::make())
                ->render(function (Author $author) {
                    return Link::make($author->id)
                        ->route('platform.author.edit', $author);
                }),
            TD::make('name', __('Name'))
                ->sort()
                ->filter(Input::make())
                ->render(function (Author $author) {
                    return Link::make($author->name)
                        ->route('platform.author.edit', $author);
                }),
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

}

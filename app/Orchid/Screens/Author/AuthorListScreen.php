<?php

namespace App\Orchid\Screens\Author;

use App\Models\Author;
use App\Orchid\Layouts\Author\AuthorListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class AuthorListScreen extends Screen
{

    public function query(): array
    {
        return [
            'authors' => Author::filters()->defaultSort('id')->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Music authors';
    }

    public function description(): ?string
    {
        return 'All music authors we know :-)';
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.author.edit'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            AuthorListLayout::class,
        ];
    }
}

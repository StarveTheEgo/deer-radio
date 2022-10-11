<?php

namespace App\Orchid\Screens\Author;

use App\Models\Author;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class AuthorEditScreen extends Screen
{
    public Author $author;

    public function query(Author $author): array
    {
        return [
            'author' => $author,
        ];
    }

    public function name(): ?string
    {
        return $this->author->exists ? 'Edit Author' : 'Creating a new Author';
    }

    public function description(): ?string
    {
        return 'Music authors';
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create Author')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->author->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->author->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->author->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('author.name')
                    ->title('Name')
                    ->help('Wow! Who is this amazing music maker this time?'),

                CheckBox::make('author.is_active')
                    ->title('Is active')
                    ->help('Author\'s music can be rotated')
                    ->sendTrueOrFalse(),

                Input::make('author.unsplash_search_query')
                    ->title('Unsplash search query')
                    ->help('Leave blank to have default search query'),
            ]),
        ];
    }

    public function createOrUpdate(Author $post, Request $request)
    {
        $post->fill($request->get('author'))->save();

        Alert::info('A new Author has been added! :-)');

        return redirect()->route('platform.author.list');
    }

    public function remove(Author $post)
    {
        $post->delete();

        Alert::info('Author is removed now! But why?');

        return redirect()->route('platform.author.list');
    }
}

<?php

namespace App\Orchid\Resources;

use App\Models\Author;
use App\Models\AuthorLink;
use App\Orchid\Filters\RelatedAuthorFilter;
use App\Orchid\Filters\RelatedFieldStringFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class AuthorLinkResource extends AbstractResource
{
    public static $model = AuthorLink::class;

    public static function icon(): string
    {
        return 'list';
    }

    public static function description(): ?string
    {
        return 'Author links (social pages, music stores)';
    }

    public function with(): array
    {
        return ['author'];
    }

    public function fields(): array
    {
        return [
            Relation::make('author_id')
                ->fromModel(Author::class, 'name')
                ->title(__('Author'))
                ->required(),

            Input::make('url')
                ->type('url')
                ->title(__('URL'))
                ->help(__('URL to the social page or store or deers :-)'))
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

            TD::make('author_id', __('Author'))
                ->render(function (AuthorLink $author_link) {
                    return $author_link->author->name;
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

            Sight::make('author_id', __('Author'))
                ->render(function (AuthorLink $author_link) {
                    return $author_link->author->name;
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
            'author_id' => [
                'required',
                'exists:'.Author::class.',id',
            ],
            'url' => [
                'required',
                'url',
                Rule::unique(self::$model, 'url')
                    ->where(function ($query) use ($model_input) {
                        return $query
                            ->where('author_id', $model_input['author_id'])
                            ->where('url', $model_input['url']);
                    })->ignore($model),

            ],
        ];
    }

    public function filters(): array
    {
        return [
            new RelatedAuthorFilter(),
        ];
    }
}

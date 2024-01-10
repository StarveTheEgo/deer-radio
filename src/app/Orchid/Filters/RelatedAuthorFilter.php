<?php

namespace App\Orchid\Filters;

use App\Models\Author;
use Orchid\Screen\Fields\Relation;

class RelatedAuthorFilter extends RelatedFieldStringFilter
{

    public function __construct()
    {
        $name = __('Author');
        $relation_name = 'author';
        $search_field = 'name';

        $field = Relation::make()
            ->fromModel(Author::class, $search_field, $search_field);

        parent::__construct($name, $relation_name, $search_field, $field);
    }

}

<?php

namespace App\Orchid\Filters;

use App\Models\Album;
use Orchid\Screen\Fields\Relation;

class RelatedAlbumFilter extends RelatedFieldStringFilter
{

    public function __construct()
    {
        $name = __('Album');
        $relation_name = 'album';
        $search_field = 'title';

        $field = Relation::make()
            ->fromModel(Album::class, $search_field, $search_field);

        parent::__construct($name, $relation_name, $search_field, $field);
    }

}

<?php

namespace App\Orchid\Filters;

use App\Models\Label;
use Orchid\Screen\Fields\Relation;

class RelatedLabelFilter extends RelatedFieldStringFilter
{

    public function __construct()
    {
        $name = __('Label');
        $relation_name = 'label';
        $search_field = 'name';

        $field = Relation::make()
            ->fromModel(Label::class, $search_field, $search_field);

        parent::__construct($name, $relation_name, $search_field, $field);
    }

}

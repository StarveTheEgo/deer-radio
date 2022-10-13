<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;

class RelatedFieldStringFilter extends Filter
{
    public $parameters = ['needle'];

    private string $name;
    private string $relationName;
    private string $searchField;

    public function __construct(string $name, string $relation_name, string $search_field)
    {
        parent::__construct();
        $this->name = $name;
        $this->relationName = $relation_name;
        $this->searchField = $search_field;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function run(Builder $builder): Builder
    {
        $needle = $this->request->get('needle');

        return $builder
            ->whereRelation($this->relationName, $this->searchField, 'like', '%'.$needle.'%');
    }

    public function display(): array
    {
        return [
            Input::make('needle')
                ->type('text')
                ->value($this->request->get('needle'))
                ->placeholder('Enter search value')
                ->title($this->name),
        ];
    }
}

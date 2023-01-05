<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class RelatedFieldStringFilter extends Filter
{
    private string $title;
    private string $relationName;
    private string $searchField;
    private ?Field $inputField;
    private string $fieldName;

    public function __construct(string $title, string $relation_name, string $search_field, ?Field $inputField = null)
    {
        parent::__construct();
        $this->title = $title;
        $this->relationName = $relation_name;
        $this->searchField = $search_field;
        $this->inputField = $inputField;
        $this->fieldName = 'filter_'.$relation_name.'_'.$search_field;
        $this->parameters = [$this->fieldName];
    }

    public function name(): string
    {
        return $this->title;
    }

    public function run(Builder $builder): Builder
    {
        $needle = $this->request->get($this->fieldName);
        if (null === $needle || '' === $needle) {
            return $builder;
        }

        return $builder
            ->whereRelation($this->relationName, $this->searchField, 'like', '%'.$needle.'%');
    }

    public function display(): array
    {
        $field = $this->inputField ?? $this->buildDefaultField();

        return [
            $field
                ->name($this->fieldName)
                ->title($this->title)
                ->value($this->request->get($this->fieldName)),
        ];
    }

    private function buildDefaultField(): Field
    {
        return
            Input::make($this->fieldName)
                ->type('text')
                ->value($this->request->get($this->fieldName))
                ->placeholder('Enter search value');
    }
}

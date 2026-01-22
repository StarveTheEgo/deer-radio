<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class RelatedFieldStringFilter extends Filter
{
    private readonly string $fieldName;

    public function __construct(private readonly string $title, private readonly string $relationName, private readonly string $searchField, private readonly ?Field $inputField = null)
    {
        parent::__construct();
        $this->fieldName = 'filter_'.$this->relationName.'_'.$this->searchField;
        $this->parameters = [$this->fieldName];
    }

    #[\Override]
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

    #[\Override]
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

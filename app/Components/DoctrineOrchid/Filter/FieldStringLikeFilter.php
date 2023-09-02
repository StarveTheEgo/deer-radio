<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid\Filter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class FieldStringLikeFilter extends AbstractDoctrineFilter
{
    private string $title;
    private string $searchField;
    private ?Field $inputField;
    private string $fieldName;

    public function __construct(string $title, string $search_field, ?Field $inputField = null)
    {
        parent::__construct();
        $this->title = $title;
        $this->searchField = $search_field;
        $this->inputField = $inputField;
        $this->fieldName = 'filter_like_'.$search_field;
        $this->parameters = [$this->fieldName];
    }

    protected function doctrineRun(QueryBuilder $builder, string $alias): QueryBuilder
    {
        $needle = $this->request->get($this->fieldName);
        if (null === $needle || '' === $needle) {
            return $builder;
        }
        // @todo check naming conflicts
        // @todo check escaping
        $criteria = Criteria::create()
            ->where(
                Criteria::expr()->contains($this->searchField, $needle)
            );

        return $builder->addCriteria($criteria);
    }

    public function name(): string
    {
        return $this->title;
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

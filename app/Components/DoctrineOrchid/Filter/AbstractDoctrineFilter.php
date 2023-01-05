<?php

namespace App\Components\DoctrineOrchid\Filter;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use LogicException;
use Orchid\Filters\Filter;

abstract class AbstractDoctrineFilter extends Filter
{
    abstract protected function doctrineRun(QueryBuilder $builder, string $alias): QueryBuilder;

    private function shouldFilter(): bool
    {
        return empty($this->parameters()) || $this->request->hasAny($this->parameters());
    }

    public function doctrineFilter(QueryBuilder $builder, string $alias): QueryBuilder
    {
        if ($this->shouldFilter()) {
            return $this->doctrineRun($builder, $alias);
        }

        return $builder;
    }

    /**
     * @deprecated
     */
    final public function run(Builder $builder): Builder
    {
        throw new LogicException('This method is not supposed to be called');
    }
}

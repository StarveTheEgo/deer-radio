<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid\Repository;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;
use LogicException;

abstract class AbstractRepository implements RepositoryInterface
{
    use PaginatesFromRequest;

    private EntityManager $entityManager;
    private ObjectRepository $entityRepository;
    private string $alias;

    public function __construct(EntityManager $entityManager, EntityRepository $entityRepository)
    {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityRepository;
        $this->alias = 'o';
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    protected function getEntityRepository(): ObjectRepository
    {
        return $this->entityRepository;
    }

    protected function createObject(AbstractDomainObject $object)
    {
        $em = $this->entityManager;
        if ($em->contains($object)) {
            $objectClass = get_class($object);
            throw new LogicException("Object {$objectClass} '{$object->getId()}' is already persisted");
        }
        $em->persist($object);
        $em->flush();
    }

    protected function updateObject(AbstractDomainObject $object)
    {
        $em = $this->entityManager;
        if (!$em->contains($object)) {
            $objectClass = get_class($object);
            throw new LogicException("Object {$objectClass} '{$object->getId()}' is not persisted");
        }
        $em->flush();
    }

    public function wrapInTransaction(callable $callback)
    {
        return $this->entityManager->wrapInTransaction($callback);
    }

    public function paginatedFindAll(int $perPage, array $filters = [], string $pageName = 'page'): LengthAwarePaginator
    {
        $queryBuilder = $this->createQueryBuilder($this->alias);
        $queryBuilder = $this->applyFiltersTo($queryBuilder, $filters);

        return $this->paginate($queryBuilder->getQuery(), $perPage, $pageName, false);
    }

    public function filteredFindAll(array $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder($this->alias);
        $queryBuilder = $this->applyFiltersTo($queryBuilder, $filters);

        return $queryBuilder->getQuery()->execute();
    }

    public function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        return $this->entityRepository->createQueryBuilder($alias, $indexBy);
    }

    /**
     * @param AbstractDoctrineFilter[] $filters
     */
    public function applyFiltersTo(QueryBuilder $builder, array $filters): QueryBuilder
    {
        foreach ($filters as $filter) {
            $builder = $filter->doctrineFilter($builder, $this->alias);
        }

        return $builder;
    }
}

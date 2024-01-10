<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid\Repository;

interface RepositoryInterface
{
    public function paginatedFindAll(int $perPage, array $filters = [], string $pageName = 'page');

    public function filteredFindAll(array $filters = []);

    public function wrapInTransaction(callable $callback);

    public function findObjectById(int $id);

    public function getObjectById(int $id);

    public function count() : int;
}

<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountReadService
{
    /**
     * @param ServiceAccountRepositoryInterface $repository
     */
    public function __construct(private readonly ServiceAccountRepositoryInterface $repository)
    {
    }

    /**
     * @param int $id
     * @return ServiceAccount
     */
    public function getById(int $id): ServiceAccount
    {
        return $this->repository->getObjectById($id);
    }

    /**
     * @param AbstractDoctrineFilter[] $filters
     * @return ServiceAccount[]
     */
    public function filteredFindAll(array $filters): array
    {
        return $this->repository->filteredFindAll($filters);
    }
}

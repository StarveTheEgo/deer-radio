<?php

declare(strict_types=1);

namespace App\Components\User\Service;

use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use App\Components\User\Entity\User;
use App\Components\User\Repository\UserRepositoryInterface;
use App\Components\Song\Entity\Song;

class UserReadService
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById(int $id): ?Song
    {
        return $this->repository->findObjectById($id);
    }

    public function getById(int $id): User
    {
        return $this->repository->getObjectById($id);
    }

    /**
     * @param AbstractDoctrineFilter[] $filters
     * @return User[]
     */
    public function filteredFindAll(array $filters): array
    {
        return $this->repository->filteredFindAll($filters);
    }

}

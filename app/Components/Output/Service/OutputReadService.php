<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepositoryInterface;
use App\Components\Song\Entity\Song;

class OutputReadService
{
    private OutputRepositoryInterface $repository;

    public function __construct(OutputRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById(int $id): ?Song
    {
        return $this->repository->findObjectById($id);
    }

    public function getById(int $id): Output
    {
        return $this->repository->getObjectById($id);
    }

    /**
     * @param AbstractDoctrineFilter[] $filters
     * @return Output[]
     */
    public function filteredFindAll(array $filters): array
    {
        return $this->repository->filteredFindAll($filters);
    }

}

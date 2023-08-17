<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use App\Components\Song\Criteria\DeerRadioSongCriteria;
use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepositoryInterface;

class SongReadService
{
    private SongRepositoryInterface $repository;

    public function __construct(SongRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getCount() : int
    {
        return $this->repository->count();
    }

    public function findById(int $id): ?Song
    {
        return $this->repository->findObjectById($id);
    }

    public function getById(int $id): Song
    {
        return $this->repository->getObjectById($id);
    }

    /**
     * @param AbstractDoctrineFilter[] $filters
     * @return mixed
     */
    public function filteredFindAll(array $filters)
    {
        return $this->repository->filteredFindAll($filters);
    }

    /**
     * @param DeerRadioSongCriteria $criteria
     * @return int[]
     */
    public function findIdsByCriteria(DeerRadioSongCriteria $criteria) : array
    {
        return $this->repository->findIdsByCriteria($criteria);
    }
}

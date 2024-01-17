<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountReadService
{
    private ServiceAccountRepositoryInterface $repository;

    public function __construct(ServiceAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getById(int $id): ServiceAccount
    {
        return $this->repository->getObjectById($id);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountCreateService
{
    private ServiceAccountRepositoryInterface $repository;

    public function __construct(ServiceAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(ServiceAccount $ServiceAccount): void
    {
        $this->repository->create($ServiceAccount);
    }
}

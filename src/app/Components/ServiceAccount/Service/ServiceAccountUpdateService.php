<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountUpdateService
{
    private ServiceAccountRepositoryInterface $repository;

    /**
     * @param ServiceAccountRepositoryInterface $repository
     */
    public function __construct(ServiceAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ServiceAccount $ServiceAccount
     * @return void
     */
    public function update(ServiceAccount $ServiceAccount): void
    {
        $this->repository->update($ServiceAccount);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service\ServiceAccount;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountCreateService
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
     * @param ServiceAccount $serviceAccount
     * @return void
     */
    public function create(ServiceAccount $serviceAccount): void
    {
        $this->repository->create($serviceAccount);
    }
}

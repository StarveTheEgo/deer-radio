<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountCreateService
{
    /**
     * @param ServiceAccountRepositoryInterface $repository
     */
    public function __construct(private readonly ServiceAccountRepositoryInterface $repository)
    {
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

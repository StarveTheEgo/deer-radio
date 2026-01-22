<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountUpdateService
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
    public function update(ServiceAccount $serviceAccount): void
    {
        $this->repository->update($serviceAccount);
    }
}

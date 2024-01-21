<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\AccessToken\Service\AccessTokenDeleteService;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountDeleteService
{
    private ServiceAccountRepositoryInterface $repository;

    private AccessTokenDeleteService $tokenDeleteService;

    /**
     * @param ServiceAccountRepositoryInterface $repository
     * @param AccessTokenDeleteService $tokenDeleteService
     */
    public function __construct(
        ServiceAccountRepositoryInterface $repository,
        AccessTokenDeleteService $tokenDeleteService
    )
    {
        $this->repository = $repository;
        $this->tokenDeleteService = $tokenDeleteService;
    }

    /**
     * @param ServiceAccount $serviceAccount
     * @return void
     */
    public function delete(ServiceAccount $serviceAccount): void
    {
        $this->repository->delete($serviceAccount);

        $accessToken = $serviceAccount->getAccessToken();
        if ($accessToken !== null) {
            $this->tokenDeleteService->delete($accessToken);
        }

    }
}

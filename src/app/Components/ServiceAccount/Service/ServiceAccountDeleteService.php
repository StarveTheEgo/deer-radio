<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Service;

use App\Components\AccessToken\Service\AccessTokenDeleteService;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;

class ServiceAccountDeleteService
{
    /**
     * @param ServiceAccountRepositoryInterface $repository
     * @param AccessTokenDeleteService $tokenDeleteService
     */
    public function __construct(private readonly ServiceAccountRepositoryInterface $repository, private readonly AccessTokenDeleteService $tokenDeleteService)
    {
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

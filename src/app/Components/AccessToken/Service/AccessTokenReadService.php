<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Service;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Repository\AccessTokenRepositoryInterface;

class AccessTokenReadService
{
    private AccessTokenRepositoryInterface $repository;

    public function __construct(AccessTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getById(int $id): AccessToken
    {
        return $this->repository->getObjectById($id);
    }

    /**
     * @return iterable<AccessToken>
     */
    public function iterateExpiredRefreshableAccessTokens(): iterable
    {
        return $this->repository->iterateExpiredRefreshableAccessTokens();
    }
}

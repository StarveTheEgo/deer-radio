<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Service;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Repository\AccessTokenRepositoryInterface;

class AccessTokenUpdateService
{
    private AccessTokenRepositoryInterface $repository;

    /**
     * @param AccessTokenRepositoryInterface $repository
     */
    public function __construct(AccessTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AccessToken $AccessToken
     * @return void
     */
    public function update(AccessToken $AccessToken): void
    {
        $this->repository->update($AccessToken);
    }
}

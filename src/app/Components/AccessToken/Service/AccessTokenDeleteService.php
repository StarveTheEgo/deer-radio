<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Service;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Repository\AccessTokenRepositoryInterface;

class AccessTokenDeleteService
{
    private AccessTokenRepositoryInterface $repository;

    public function __construct(AccessTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function delete(AccessToken $AccessToken): void
    {
        $this->repository->delete($AccessToken);
    }
}

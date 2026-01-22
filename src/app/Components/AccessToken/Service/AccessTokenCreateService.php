<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Service;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Repository\AccessTokenRepositoryInterface;

class AccessTokenCreateService
{
    public function __construct(private readonly AccessTokenRepositoryInterface $repository)
    {
    }

    public function create(AccessToken $AccessToken): void
    {
        $this->repository->create($AccessToken);
    }
}

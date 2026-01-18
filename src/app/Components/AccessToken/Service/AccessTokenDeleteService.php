<?php

declare(strict_types=1);

namespace App\Components\AccessToken\Service;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Repository\AccessTokenRepositoryInterface;

class AccessTokenDeleteService
{
    public function __construct(private readonly AccessTokenRepositoryInterface $repository)
    {
    }

    public function delete(AccessToken $AccessToken): void
    {
        $this->repository->delete($AccessToken);
    }
}

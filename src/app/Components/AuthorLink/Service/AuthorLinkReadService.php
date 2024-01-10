<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Service;

use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;

class AuthorLinkReadService
{
    private AuthorLinkRepositoryInterface $repository;

    public function __construct(AuthorLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

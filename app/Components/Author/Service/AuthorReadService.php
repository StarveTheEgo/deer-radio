<?php

declare(strict_types=1);

namespace App\Components\Author\Service;

use App\Components\Author\Repository\AuthorRepositoryInterface;

class AuthorReadService
{
    private AuthorRepositoryInterface $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

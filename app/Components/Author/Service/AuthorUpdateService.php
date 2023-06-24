<?php

declare(strict_types=1);

namespace App\Components\Author\Service;

use App\Components\Author\Entity\Author;
use App\Components\Author\Repository\AuthorRepositoryInterface;

class AuthorUpdateService
{
    private AuthorRepositoryInterface $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(Author $author): void
    {
        $this->repository->update($author);
    }
}

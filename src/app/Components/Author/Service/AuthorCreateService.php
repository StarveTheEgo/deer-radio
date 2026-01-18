<?php

declare(strict_types=1);

namespace App\Components\Author\Service;

use App\Components\Author\Entity\Author;
use App\Components\Author\Repository\AuthorRepositoryInterface;

class AuthorCreateService
{
    public function __construct(private readonly AuthorRepositoryInterface $repository)
    {
    }

    public function create(Author $author): void
    {
        $this->repository->create($author);
    }
}

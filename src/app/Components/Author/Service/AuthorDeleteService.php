<?php

declare(strict_types=1);

namespace App\Components\Author\Service;

use App\Components\Author\Entity\Author;
use App\Components\Author\Repository\AuthorRepositoryInterface;

class AuthorDeleteService
{
    public function __construct(private readonly AuthorRepositoryInterface $repository)
    {
    }

    public function delete(Author $author): void
    {
        $this->repository->delete($author);
    }
}

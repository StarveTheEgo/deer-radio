<?php

declare(strict_types=1);

namespace App\Components\Author\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Author\Entity\Author;

interface AuthorRepositoryInterface extends RepositoryInterface
{
    public function create(Author $author);

    public function update(Author $author): void;

    public function delete(Author $author): void;
}

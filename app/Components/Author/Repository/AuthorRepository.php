<?php

declare(strict_types=1);

namespace App\Components\Author\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Author\Entity\Author;

class AuthorRepository extends AbstractRepository implements AuthorRepositoryInterface
{
    public function create(Author $author): void
    {
        parent::createObject($author);
    }

    public function update(Author $author): void
    {
        parent::updateObject($author);
    }

    public function delete(Author $author): void
    {
        parent::deleteObject($author);
    }
}

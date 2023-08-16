<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\AuthorLink\Entity\AuthorLink;

class AuthorLinkRepository extends AbstractRepository implements AuthorLinkRepositoryInterface
{
    public function create(AuthorLink $authorLink): void
    {
        parent::createObject($authorLink);
    }

    public function update(AuthorLink $authorLink): void
    {
        parent::updateObject($authorLink);
    }

    public function delete(AuthorLink $authorLink): void
    {
        parent::deleteObject($authorLink);
    }
}

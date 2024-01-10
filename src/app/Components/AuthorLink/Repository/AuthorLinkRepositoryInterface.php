<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\AuthorLink\Entity\AuthorLink;

interface AuthorLinkRepositoryInterface extends RepositoryInterface
{
    public function create(AuthorLink $authorLink);

    public function update(AuthorLink $authorLink): void;

    public function delete(AuthorLink $authorLink): void;
}

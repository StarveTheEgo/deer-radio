<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Service;

use App\Components\AuthorLink\Entity\AuthorLink;
use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;

class AuthorLinkUpdateService
{
    private AuthorLinkRepositoryInterface $repository;

    public function __construct(AuthorLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(AuthorLink $authorLink): void
    {
        $this->repository->update($authorLink);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Service;

use App\Components\AuthorLink\Entity\AuthorLink;
use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;

class AuthorLinkDeleteService
{
    private AuthorLinkRepositoryInterface $repository;

    public function __construct(AuthorLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function delete(AuthorLink $authorLink): void
    {
        $this->repository->delete($authorLink);
    }
}

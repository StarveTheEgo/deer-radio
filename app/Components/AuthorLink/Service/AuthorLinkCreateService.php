<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Service;

use App\Components\AuthorLink\Entity\AuthorLink;
use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;

class AuthorLinkCreateService
{
    private AuthorLinkRepositoryInterface $repository;

    public function __construct(AuthorLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(AuthorLink $authorLink): void
    {
        $this->repository->create($authorLink);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Service;

use App\Components\AuthorLink\Entity\AuthorLink;
use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;

class AuthorLinkDeleteService
{
    public function __construct(private readonly AuthorLinkRepositoryInterface $repository)
    {
    }

    public function delete(AuthorLink $authorLink): void
    {
        $this->repository->delete($authorLink);
    }
}

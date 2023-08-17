<?php

declare(strict_types=1);

namespace App\Components\Author\Service;

use App\Components\Author\Repository\AuthorRepositoryInterface;
use DateTimeImmutable;

class AuthorReadService
{
    private AuthorRepositoryInterface $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getLeastPlayedAuthorIds(DateTimeImmutable $maxFinishedAt) : array
    {
        return $this->repository->getLeastPlayedAuthorIds($maxFinishedAt);
    }
}

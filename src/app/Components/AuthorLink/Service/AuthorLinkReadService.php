<?php

declare(strict_types=1);

namespace App\Components\AuthorLink\Service;

use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;

class AuthorLinkReadService
{
    public function __construct(private readonly AuthorLinkRepositoryInterface $repository)
    {
    }
}

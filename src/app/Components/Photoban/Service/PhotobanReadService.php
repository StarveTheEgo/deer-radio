<?php

declare(strict_types=1);

namespace App\Components\Photoban\Service;

use App\Components\Photoban\Entity\Photoban;
use App\Components\Photoban\Repository\PhotobanRepositoryInterface;

class PhotobanReadService
{
    public function __construct(private readonly PhotobanRepositoryInterface $repository)
    {
    }

    public function findByUrl(string $imageUrl): ?Photoban
    {
        return $this->repository->findByUrl($imageUrl);
    }
}

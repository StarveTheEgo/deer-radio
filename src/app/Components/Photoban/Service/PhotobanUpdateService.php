<?php

declare(strict_types=1);

namespace App\Components\Photoban\Service;

use App\Components\Photoban\Entity\Photoban;
use App\Components\Photoban\Repository\PhotobanRepositoryInterface;

class PhotobanUpdateService
{
    public function __construct(private readonly PhotobanRepositoryInterface $repository)
    {
    }

    public function update(Photoban $photoban): void
    {
        $this->repository->update($photoban);
    }
}

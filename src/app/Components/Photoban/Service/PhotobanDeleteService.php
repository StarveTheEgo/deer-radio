<?php

declare(strict_types=1);

namespace App\Components\Photoban\Service;

use App\Components\Photoban\Entity\Photoban;
use App\Components\Photoban\Repository\PhotobanRepositoryInterface;

class PhotobanDeleteService
{
    private PhotobanRepositoryInterface $repository;

    public function __construct(PhotobanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function delete(Photoban $photoban): void
    {
        $this->repository->delete($photoban);
    }
}

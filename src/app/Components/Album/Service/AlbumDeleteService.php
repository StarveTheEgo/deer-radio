<?php

declare(strict_types=1);

namespace App\Components\Album\Service;

use App\Components\Album\Entity\Album;
use App\Components\Album\Repository\AlbumRepositoryInterface;

class AlbumDeleteService
{
    private AlbumRepositoryInterface $repository;

    public function __construct(AlbumRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function delete(Album $album): void
    {
        $this->repository->delete($album);
    }
}

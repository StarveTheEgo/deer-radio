<?php

declare(strict_types=1);

namespace App\Components\Album\Service;

use App\Components\Album\Entity\Album;
use App\Components\Album\Repository\AlbumRepositoryInterface;

class AlbumCreateService
{
    public function __construct(private readonly AlbumRepositoryInterface $repository)
    {
    }

    public function create(Album $album): void
    {
        $this->repository->create($album);
    }
}

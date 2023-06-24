<?php

declare(strict_types=1);

namespace App\Components\Album\Service;

use App\Components\Album\Repository\AlbumRepositoryInterface;

class AlbumReadService
{
    private AlbumRepositoryInterface $repository;

    public function __construct(AlbumRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

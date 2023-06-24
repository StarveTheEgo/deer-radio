<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\Song\Repository\SongRepositoryInterface;

class SongReadService
{
    private SongRepositoryInterface $repository;

    public function __construct(SongRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

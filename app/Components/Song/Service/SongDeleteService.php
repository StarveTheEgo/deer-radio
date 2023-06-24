<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepositoryInterface;

class SongDeleteService
{
    private SongRepositoryInterface $repository;

    public function __construct(SongRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function delete(Song $song): void
    {
        $this->repository->delete($song);
    }
}

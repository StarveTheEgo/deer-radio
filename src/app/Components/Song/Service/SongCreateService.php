<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepositoryInterface;

class SongCreateService
{
    public function __construct(private readonly SongRepositoryInterface $repository)
    {
    }

    public function create(Song $song): void
    {
        $this->repository->create($song);
    }
}

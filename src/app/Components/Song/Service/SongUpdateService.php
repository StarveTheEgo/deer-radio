<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepositoryInterface;

class SongUpdateService
{
    public function __construct(private readonly SongRepositoryInterface $repository)
    {
    }

    public function update(Song $song): void
    {
        $this->repository->update($song);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Song\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Song\Entity\Song;

interface SongRepositoryInterface extends RepositoryInterface
{
    public function create(Song $song);

    public function update(Song $song): void;

    public function delete(Song $song): void;
}

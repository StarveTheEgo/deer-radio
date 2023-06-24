<?php

declare(strict_types=1);

namespace App\Components\Song\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Song\Entity\Song;

class SongRepository extends AbstractRepository implements SongRepositoryInterface
{
    public function create(Song $song): void
    {
        parent::createObject($song);
    }

    public function update(Song $song): void
    {
        parent::updateObject($song);
    }

    public function delete(Song $song): void
    {
        parent::deleteObject($song);
    }
}

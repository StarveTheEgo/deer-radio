<?php

declare(strict_types=1);

namespace App\Components\Album\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Album\Entity\Album;

class AlbumRepository extends AbstractRepository implements AlbumRepositoryInterface
{
    public function create(Album $album): void
    {
        parent::createObject($album);
    }

    public function update(Album $album): void
    {
        parent::updateObject($album);
    }

    public function delete(Album $album): void
    {
        parent::deleteObject($album);
    }
}

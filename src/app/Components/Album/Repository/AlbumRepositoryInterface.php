<?php

declare(strict_types=1);

namespace App\Components\Album\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Album\Entity\Album;

interface AlbumRepositoryInterface extends RepositoryInterface
{
    public function create(Album $album);

    public function update(Album $album): void;

    public function delete(Album $album): void;
}

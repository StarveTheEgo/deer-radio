<?php

declare(strict_types=1);

namespace App\Components\Album\Service;

use App\Components\Album\Repository\AlbumRepositoryInterface;

class AlbumReadService
{
    public function __construct(private readonly AlbumRepositoryInterface $repository)
    {
    }
}

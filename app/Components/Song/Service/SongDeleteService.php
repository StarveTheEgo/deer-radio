<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepositoryInterface;
use Illuminate\Filesystem\FilesystemManager;

class SongDeleteService
{
    private SongRepositoryInterface $repository;

    private FilesystemManager $filesystemManager;

    public function __construct(
        SongRepositoryInterface $repository,
        FilesystemManager $filesystemManager
    )
    {
        $this->repository = $repository;
        $this->filesystemManager = $filesystemManager;
    }

    public function delete(Song $song): void
    {
        $songAttachment = $song->getSongAttachment();
        if ($songAttachment !== null) {
            $disk = $this->filesystemManager->get($songAttachment->getDisk());
            $disk->delete($songAttachment->getPath());
        }

        $this->repository->delete($song);
    }
}

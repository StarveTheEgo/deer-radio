<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

class SongDeleteService
{
    public function __construct(private readonly SongRepositoryInterface $repository, private readonly FilesystemManager $filesystemManager)
    {
    }

    /**
     * @param Song $song
     * @return void
     */
    public function delete(Song $song): void
    {
        $songAttachment = $song->getSongAttachment();
        if ($songAttachment !== null) {
            $disk = $this->filesystemManager->disk($songAttachment->getDisk());
            $disk->delete($songAttachment->getPath());
        }

        $this->repository->delete($song);
    }
}

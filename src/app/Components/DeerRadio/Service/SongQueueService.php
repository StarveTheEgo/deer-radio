<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\Song\Entity\Song;
use App\Components\Song\Service\SongReadService;

class SongQueueService
{
    private DeerRadioDataAccessor $dataAccessor;

    private SongReadService $songReadService;

    public function __construct(
        DeerRadioDataAccessor $dataAccessor,
        SongReadService $songReadService,
    )
    {
        $this->dataAccessor = $dataAccessor;
        $this->songReadService = $songReadService;
    }

    /**
     * Enqueues specified song
     * @param Song $song
     * @return void
     */
    public function enqueueSong(Song $song) : void
    {
        $this->dataAccessor->setValue(DeerRadioDataKey::NEXT_SONG_ID->value, $song->getId());
    }

    /**
     * Gets currently queued song
     * @return Song|null
     */
    public function getQueuedSong() : ?Song
    {
        $currentSongId = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_SONG_ID->value);
        if (!$currentSongId) {
            return null;
        }

        return $this->songReadService->findById($currentSongId);
    }
}

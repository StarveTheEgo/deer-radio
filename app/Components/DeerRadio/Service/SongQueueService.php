<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\Song\Entity\Song;
use App\Components\Song\Service\SongServiceRegistry;
use Psr\Log\LoggerInterface;

class SongQueueService
{
    private DeerRadioDataAccessor $dataAccessor;
    private SongServiceRegistry $songServiceRegistry;
    private SongPickService $songPickService;
    private LoggerInterface $logger;

    public function __construct(
        DeerRadioDataAccessor $dataAccessor,
        SongServiceRegistry $songServiceRegistry,
        SongPickService $songPickService,
        LoggerInterface $logger
    )
    {
        $this->dataAccessor = $dataAccessor;
        $this->songServiceRegistry = $songServiceRegistry;
        $this->songPickService = $songPickService;
        $this->logger = $logger;
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

        return $this->songServiceRegistry->getReadService()->findById($currentSongId);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\Author\Service\AuthorUpdateService;
use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\Song\Service\SongReadService;
use App\Components\Song\Service\SongUpdateService;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

class CurrentSongUpdateService
{
    private DeerRadioDataAccessor $dataAccessor;
    private SongUpdateService $songUpdateService;
    private SongReadService $songReadService;
    private AuthorUpdateService $authorUpdateService;
    private LoggerInterface $logger;

    public function __construct(
        DeerRadioDataAccessor $dataAccessor,
        SongUpdateService   $songUpdateService,
        SongReadService   $songReadService,
        AuthorUpdateService   $authorUpdateService,
        LoggerInterface       $logger
    )
    {
        $this->dataAccessor = $dataAccessor;
        $this->songUpdateService = $songUpdateService;
        $this->songReadService = $songReadService;
        $this->authorUpdateService = $authorUpdateService;
        $this->logger = $logger;
    }

    /**
     * Updates ID of currently playing track
     * @param int $queuedSongId
     */
    public function updateCurrentSongId(int $queuedSongId) : void
    {
        $now = new DateTimeImmutable();
        $current_song_id = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_SONG_ID->value);
        if ($current_song_id) {
            $previousSong = $this->songReadService->findById($current_song_id);
            if ($previousSong !== null) {
                // update finishing time for current song and author
                $previousSong->setFinishedAt($now);
                $this->songUpdateService->update($previousSong);

                $previousSongAuthor = $previousSong->getAuthor();
                $previousSongAuthor->setFinishedAt($now);
                $this->authorUpdateService->update($previousSongAuthor);
            }
        }

        // get the currently playing song
        $nowPlayingSong = $this->songReadService->getById($queuedSongId);

        $this->dataAccessor->setValue(DeerRadioDataKey::CURRENT_SONG_ID->value, $nowPlayingSong->getId());

        // update stats of this song
        $nowPlayingSong->setPlayedAt($now);
        $nowPlayingSong->setPlayedCount($nowPlayingSong->getPlayedCount() + 1);
        $this->songUpdateService->update($nowPlayingSong);

        // and stats of its author
        $nowPlayingAuthor = $nowPlayingSong->getAuthor();
        $nowPlayingAuthor->setPlayedAt($now);
        $nowPlayingAuthor->setPlayedCount($nowPlayingAuthor->getPlayedCount() + 1);
        $this->authorUpdateService->update($nowPlayingAuthor);

        $this->logger->info(sprintf(
            'Now playing song #%s: %s - %s',
            $nowPlayingSong->getId(),
            $nowPlayingAuthor->getName(),
            $nowPlayingSong->getTitle()
        ));
    }
}

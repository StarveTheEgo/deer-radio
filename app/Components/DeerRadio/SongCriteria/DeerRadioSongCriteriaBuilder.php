<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\SongCriteria;

use App\Components\Author\Service\AuthorReadService;
use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\DeerRadio\Enum\SongManagerSettingKey;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Song\Criteria\DeerRadioSongCriteria;
use App\Components\Song\Service\SongReadService;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

class DeerRadioSongCriteriaBuilder
{
    private SettingReadService $settingReadService;

    private SongReadService $songReadService;

    private DeerRadioDataAccessor $dataAccessor;

    private AuthorReadService $authorReadService;

    private LoggerInterface $logger;

    public function __construct(
        SettingReadService $settingReadService,
        SongReadService $songReadService,
        AuthorReadService $authorReadService,
        DeerRadioDataAccessor $dataAccessor,
        LoggerInterface $logger
    )
    {
        $this->settingReadService = $settingReadService;
        $this->songReadService = $songReadService;
        $this->authorReadService = $authorReadService;
        $this->dataAccessor = $dataAccessor;
        $this->logger = $logger;
    }

    /**
     * Builds next song criteria
     * @param DateTimeImmutable $currentTime
     * @return DeerRadioSongCriteria
     */
    public function buildNextSongCriteria(DateTimeImmutable $currentTime) : DeerRadioSongCriteria
    {
        $criteria = new DeerRadioSongCriteria();

        $criteria->setLimit($this->calculateLimit());

        $currentSongId = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_SONG_ID->value);
        if ($currentSongId) {
            $criteria = $this->avoidSongAndAuthor($criteria, $currentSongId);
        }

        $queuedSongId = $this->dataAccessor->getValue(DeerRadioDataKey::NEXT_SONG_ID->value);
        if ($queuedSongId) {
            $criteria = $this->avoidSongAndAuthor($criteria, $queuedSongId);
        }

        $suitableAuthorIds = $this->calculateSuitableAuthorIds($currentTime);
        foreach ($suitableAuthorIds as $authorId) {
            $criteria->addSuitableAuthorId($authorId);
        }

        $criteria->setMaxSongFinishTime($this->calculateMaxSongFinishTime($currentTime));

        return $criteria;
    }

    /**
     * @return int
     */
    private function calculateLimit() : int
    {
        $totalSongs = $this->songReadService->getCount();
        $leastPlayedPercentage = (int) $this->settingReadService->getValue(SongManagerSettingKey::LEAST_PLAYED_COUNT_PERCENTAGE->value, '10');
        return (int) round($totalSongs * ($leastPlayedPercentage / 100));
    }

    /**
     * @return int[]
     */
    private function calculateSuitableAuthorIds(DateTimeImmutable $currentTime) : array
    {
        $authorInterval = (int) $this->settingReadService->getValue(SongManagerSettingKey::AUTHOR_INTERVAL->value, '10');
        $authorTimeThreshold = $currentTime->modify(sprintf('-%d minutes', $authorInterval));
        return $this->authorReadService->getLeastPlayedAuthorIds($authorTimeThreshold);
    }

    /**
     * @param DateTimeImmutable $currentTime
     * @return DateTimeImmutable
     */
    private function calculateMaxSongFinishTime(DateTimeImmutable $currentTime) : DateTimeImmutable
    {
        $songInterval = (int) $this->settingReadService->getValue(SongManagerSettingKey::SONG_INTERVAL->value, '30');
        return $currentTime->modify(sprintf('-%d minutes', $songInterval));
    }

    /**
     * @param DeerRadioSongCriteria $criteria
     * @param int $songId
     * @return DeerRadioSongCriteria
     */
    private function avoidSongAndAuthor(DeerRadioSongCriteria $criteria, int $songId) : DeerRadioSongCriteria
    {
        $criteria->addAvoidableSongId($songId);

        $currentSong = $this->songReadService->findById($songId);
        if ($currentSong !== null) {
            $criteria->addAvoidableSongId($currentSong->getAuthor()->getId());
        } else {
            $this->logger->error(sprintf('Could not find current song (#%d) in the database', $songId));
        }

        return $criteria;
    }
}

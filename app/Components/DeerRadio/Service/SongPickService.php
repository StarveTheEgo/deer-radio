<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\Enum\SongManagerSettingKey;
use App\Components\DeerRadio\SongCriteria\DeerRadioSongCriteriaBuilder;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Song\Entity\Song;
use App\Components\Song\Service\SongReadService;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

class SongPickService
{
    private SettingReadService $settingReadService;

    private SongReadService $songReadService;

    private DeerRadioSongCriteriaBuilder $songCriteriaBuilder;

    private LoggerInterface $logger;

    public function __construct(
        SettingReadService $settingReadService,
        SongReadService $songReadService,
        DeerRadioSongCriteriaBuilder $songCriteriaBuilder,
        LoggerInterface $logger
    )
    {
        $this->settingReadService = $settingReadService;
        $this->songReadService = $songReadService;
        $this->songCriteriaBuilder = $songCriteriaBuilder;
        $this->logger = $logger;
    }

    public function pickNextSong() : ?Song
    {
        $suitableSongIds = $this->getSuitableSongIds();
        if (!$suitableSongIds) {
            return null;
        }

        $songId = $suitableSongIds[array_rand($suitableSongIds)];
        return $this->songReadService->getById($songId);
    }

    public function getSuitableSongIds() : array
    {
        $isEnabled = (bool) $this->settingReadService->getValue(SongManagerSettingKey::IS_ENABLED->value, '0');
        if ($isEnabled) {
            return [];
        }

        $criteria = $this->songCriteriaBuilder->buildNextSongCriteria($this->getCurrentTime());

        $songIds = $this->songReadService->findIdsByCriteria($criteria);
        if (count($songIds) > 0) {
            return $songIds;
        }

        // fallback options
        $suitableAuthorIds = $criteria->getSuitableAuthorIds();
        if ($suitableAuthorIds) {
            $this->logger->error('Not enough songs for authors: '.implode(', ', $suitableAuthorIds));

            // disable suitable authors filter if it was active, then try to get song ids again
            $criteria->clearSuitableAuthorIds();
            $songIds = $this->songReadService->findIdsByCriteria($criteria);
            if (count($songIds) > 0) {
                return $songIds;
            }
        }

        if ($criteria->getMaxSongFinishTime() !== null) {
            $this->logger->error('Had to fallback in order to get next song');
            $criteria->setMaxSongFinishTime(null);
            return $this->songReadService->findIdsByCriteria($criteria);
        }

        return [];
    }

    private function getCurrentTime() : DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}

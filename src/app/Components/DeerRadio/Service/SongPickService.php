<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\Enum\SongManagerSettingKey;
use App\Components\DeerRadio\SongCriteria\DeerRadioSongCriteriaBuilder;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Song\Criteria\DeerRadioSongCriteria;
use App\Components\Song\Entity\Song;
use App\Components\Song\Service\SongReadService;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

class SongPickService
{
    public function __construct(private readonly SettingReadService $settingReadService, private readonly SongReadService $songReadService, private readonly DeerRadioSongCriteriaBuilder $songCriteriaBuilder, private readonly LoggerInterface $logger)
    {
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

            return $this->songReadService->findIdsByCriteria(new DeerRadioSongCriteria());
        }

        return [];
    }

    private function getCurrentTime() : DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Song\Criteria;

use DateTimeImmutable;

class DeerRadioSongCriteria
{
    /** @var array<int> */
    private array $avoidableSongIds = [];

    /** @var array<int> */
    private array $avoidableAuthorIds = [];

    /** @var DateTimeImmutable|null */
    private ?DateTimeImmutable $maxSongFinishTime = null;

    /** @var array<int> */
    private array $suitableAuthorIds = [];

    /** @var int|null */
    private ?int $limit = null;

    /**
     * @return int[]
     */
    public function getAvoidableSongIds(): array
    {
        return $this->avoidableSongIds;
    }

    /**
     * @param int $songId
     * @return $this
     */
    public function addAvoidableSongId(int $songId) : DeerRadioSongCriteria
    {
        $this->avoidableSongIds[$songId] = $songId;
        return $this;
    }

    /**
     * @return array<int>
     */
    public function getAvoidableAuthorIds(): array
    {
        return $this->avoidableAuthorIds;
    }

    /**
     * @param int $authorId
     * @return $this
     */
    public function addAvoidableAuthorId(int $authorId) : DeerRadioSongCriteria
    {
        $this->avoidableAuthorIds[$authorId] = $authorId;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getMaxSongFinishTime(): ?DateTimeImmutable
    {
        return $this->maxSongFinishTime;
    }

    /**
     * @param DateTimeImmutable|null $maxSongFinishTime
     * @return DeerRadioSongCriteria
     */
    public function setMaxSongFinishTime(?DateTimeImmutable $maxSongFinishTime): DeerRadioSongCriteria
    {
        $this->maxSongFinishTime = $maxSongFinishTime;
        return $this;
    }

    /**
     * @return array<int>
     */
    public function getSuitableAuthorIds(): array
    {
        return $this->suitableAuthorIds;
    }

    /**
     * @param int $authorId
     * @return $this
     */
    public function addSuitableAuthorId(int $authorId) : DeerRadioSongCriteria
    {
        $this->suitableAuthorIds[$authorId] = $authorId;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearSuitableAuthorIds() : DeerRadioSongCriteria
    {
        $this->suitableAuthorIds = [];

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }
}

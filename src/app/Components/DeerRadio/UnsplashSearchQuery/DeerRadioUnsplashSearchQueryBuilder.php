<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\UnsplashSearchQuery;

use App\Components\DeerRadio\DeerRadioDataAccessor;
use App\Components\DeerRadio\Enum\DeerRadioDataKey;
use App\Components\DeerRadio\Enum\UnsplashSearchQueryBuilderSettingKey;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Song\Service\SongReadService;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQuery;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryType;
use App\Song;

/**
 * Builds unsplash search query based on app settings
 */
class DeerRadioUnsplashSearchQueryBuilder implements UnsplashSearchQueryBuilderInterface
{
    /** @var SettingReadService  */
    private SettingReadService $settingReadService;

    /** @var DeerRadioDataAccessor */
    private DeerRadioDataAccessor $dataAccessor;

    /** @var SongReadService */
    private SongReadService $songReadService;

    /**
     * @param SettingReadService $settingReadService
     * @param DeerRadioDataAccessor $dataAccessor
     * @param SongReadService $songReadService
     */
    public function __construct(
        SettingReadService $settingReadService,
        DeerRadioDataAccessor $dataAccessor,
        SongReadService $songReadService
    )
    {
        $this->settingReadService = $settingReadService;
        $this->dataAccessor = $dataAccessor;
        $this->songReadService = $songReadService;
    }

    /**
     * @return UnsplashSearchQuery
     */
    public function buildSearchQuery() : UnsplashSearchQuery
    {
        $parameters = [];

        $prompt = $this->getCurrentPrompt();
        if ($prompt !== null) {
            $parameters['query'] = $prompt;
        }

        $parameters['count'] = (int) $this->settingReadService->getValue(UnsplashSearchQueryBuilderSettingKey::IMAGE_LIST_COUNT->value, '1');

        return new UnsplashSearchQuery(UnsplashSearchQueryType::RANDOM, $parameters);
    }

    /**
     * @return string|null
     */
    private function getCurrentPrompt() : ?string
    {
        $prompt = null;

        $isCustomPromptEnabled = UnsplashSearchQueryBuilderSettingKey::CUSTOM_PROMPT_ENABLED->value;
        if ($isCustomPromptEnabled) {
            // try to get custom prompt
            $prompt = $this->getCurrentCustomPrompt();
        }

        if ($prompt === null) {
            $prompt = $this->settingReadService->getValue(UnsplashSearchQueryBuilderSettingKey::DEFAULT_SEARCH_PROMPT->value);
        }

        return $prompt;
    }

    /**
     * @return string|null
     */
    private function getCurrentCustomPrompt() : ?string
    {
        $currentSongId = $this->dataAccessor->getValue(DeerRadioDataKey::CURRENT_SONG_ID->value);
        if ($currentSongId === null) {
            return null;
        }

        $currentSong = $this->songReadService->getById($currentSongId);

        // try to get custom prompt from the current song
        $songPrompt = $currentSong->getUnsplashSearchQuery();
        if ($songPrompt !== null && $songPrompt !== '') {
            return $songPrompt;
        }

        // try to get custom prompt from the current author
        $authorPrompt = $currentSong->getAuthor()?->getUnsplashSearchQuery();
        if ($authorPrompt !== null && $authorPrompt !== '') {
            return $authorPrompt;
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\UnsplashSearchQuery;

use App\Components\Setting\Service\SettingReadService;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQuery;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryType;

/**
 * Builds unsplash search query based on app settings
 */
class DeerRadioUnsplashSearchQueryBuilder implements UnsplashSearchQueryBuilderInterface
{
    public const SETTING_DEFAULT_SEARCH_PROMPT = 'unsplash-query.default_search_prompt';

    public const SETTING_IMAGE_LIST_COUNT = 'unsplash-query.image_list_count';

    private SettingReadService $settingReadService;

    public function __construct(SettingReadService $settingReadService) {
        $this->settingReadService = $settingReadService;
    }

    public function buildSearchQuery() : UnsplashSearchQuery {
        $queryType = UnsplashSearchQueryType::RANDOM;
        $parameters = [];

        $prompt = $this->settingReadService->getValue(self::SETTING_DEFAULT_SEARCH_PROMPT);
        // @todo custom prompt per song/author
        if ($prompt !== null) {
            $parameters['query'] = $prompt;
        }
        $parameters['count'] = (int) $this->settingReadService->getValue(self::SETTING_IMAGE_LIST_COUNT, '1');

        return new UnsplashSearchQuery($queryType, $parameters);
    }
}

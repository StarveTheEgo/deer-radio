<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\UnsplashSearchQuery;

use App\Components\DeerRadio\Enum\UnsplashSearchQueryBuilderSettingKey;
use App\Components\Setting\Service\SettingReadService;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQuery;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryType;

/**
 * Builds unsplash search query based on app settings
 */
class DeerRadioUnsplashSearchQueryBuilder implements UnsplashSearchQueryBuilderInterface
{

    private SettingReadService $settingReadService;

    public function __construct(SettingReadService $settingReadService) {
        $this->settingReadService = $settingReadService;
    }

    public function buildSearchQuery() : UnsplashSearchQuery {
        $queryType = UnsplashSearchQueryType::RANDOM;
        $parameters = [];

        $prompt = $this->settingReadService->getValue(UnsplashSearchQueryBuilderSettingKey::DEFAULT_SEARCH_PROMPT->value);
        // @todo custom prompt per song/author
        if ($prompt !== null) {
            $parameters['query'] = $prompt;
        }
        $parameters['count'] = (int) $this->settingReadService->getValue(UnsplashSearchQueryBuilderSettingKey::IMAGE_LIST_COUNT->value, '1');

        return new UnsplashSearchQuery($queryType, $parameters);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\ImageData\Driver\Unsplash;

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

        $prompt = $this->settingReadService->getValue('unsplash.default_search_prompt');
        // @todo custom prompt per song/author
        if ($prompt !== null) {
            $parameters['query'] = $prompt;
        }
        $parameters['count'] = $this->settingReadService->getValue('unsplash.image_list_count', 1);

        return new UnsplashSearchQuery($queryType, $parameters);
    }
}

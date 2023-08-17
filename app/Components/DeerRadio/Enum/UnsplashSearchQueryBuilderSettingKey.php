<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Enum;

enum UnsplashSearchQueryBuilderSettingKey: string
{
    case DEFAULT_SEARCH_PROMPT = 'unsplash-query.default_search_prompt';

    case IMAGE_LIST_COUNT = 'unsplash-query.image_list_count';
}

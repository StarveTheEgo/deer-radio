<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient\UnsplashQuery;

enum UnsplashSearchQueryType: string
{
    /**
     * @see https://unsplash.com/documentation#get-a-random-photo
     */
    case RANDOM = 'random';
}

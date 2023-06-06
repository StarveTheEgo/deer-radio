<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient\UnsplashQuery;

interface UnsplashSearchQueryBuilderInterface
{
    public function buildSearchQuery(): UnsplashSearchQuery;
}

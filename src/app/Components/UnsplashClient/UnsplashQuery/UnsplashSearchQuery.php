<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient\UnsplashQuery;

class UnsplashSearchQuery
{
    /**
     * @param UnsplashSearchQueryType $queryType
     * @param array<string, mixed>|null $parameters
     */
    public function __construct(private readonly UnsplashSearchQueryType $queryType, private readonly ?array $parameters = null)
    {
    }

    /**
     * @return UnsplashSearchQueryType
     */
    public function getQueryType(): UnsplashSearchQueryType
    {
        return $this->queryType;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }
}

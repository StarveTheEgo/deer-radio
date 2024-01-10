<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient\UnsplashQuery;

class UnsplashSearchQuery
{
    private UnsplashSearchQueryType $queryType;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $parameters;

    /**
     * @param UnsplashSearchQueryType $queryType
     * @param array<string, mixed>|null $parameters
     */
    public function __construct(UnsplashSearchQueryType $queryType, ?array $parameters = null)
    {
        $this->queryType = $queryType;
        $this->parameters = $parameters;
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

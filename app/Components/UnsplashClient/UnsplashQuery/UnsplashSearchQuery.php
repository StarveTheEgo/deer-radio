<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient\UnsplashQuery;

class UnsplashSearchQuery
{
    private UnsplashSearchQueryType $queryType;
    private ?array $parameters;

    public function __construct(UnsplashSearchQueryType $queryType, ?array $parameters = null) {
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
     * @return array|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }
}

<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient;

use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQuery;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryType;
use LogicException;
use Unsplash\HttpClient;
use Unsplash\Photo;

class UnsplashClient
{
    private bool $isInitiated = false;
    private string $appId;
    private string $appSecret;
    private string $appName;

    public function __construct(string $appId, string $appSecret, string $appName)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->appName = $appName;
    }

    /**
     * @return void
     */
    private function ensureVendorClientInit() : void {
        if ($this->isInitiated) {
            return;
        }

        HttpClient::init([
            'applicationId'	=> $this->appId,
            'secret'		=> $this->appSecret,
            'utmSource'     => $this->appName,
        ]);

        $this->isInitiated = true;
    }

    /**
     * @param UnsplashSearchQuery $searchQuery
     * @return array
     */
    public function runSearchQuery(UnsplashSearchQuery $searchQuery) : array
    {
        $this->ensureVendorClientInit();
        $queryType = $searchQuery->getQueryType();

        if ($queryType === UnsplashSearchQueryType::RANDOM) {
            return Photo::random($searchQuery->getParameters() ?? [])->toArray();
        }

        throw new LogicException(sprintf(
            'Unsupported search query type %s',
            $queryType->value
        ));
    }
}

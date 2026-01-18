<?php

declare(strict_types=1);

namespace App\Components\Liquidsoap\Factory;

use GuzzleHttp\Client as HttpClient;

class LiquidsoapHttpClientFactory
{
    /**
     * @param string $liquidsoapUrl
     */
    public function __construct(private readonly string $liquidsoapUrl)
    {
    }

    /**
     * @return HttpClient
     */
    public function createHttpClient(): HttpClient
    {
        return new HttpClient([
            'base_uri' => $this->liquidsoapUrl,
            'headers' => [
                'content-type' => 'application/json'
            ],
            'timeout' => 30,
        ]);
    }
}

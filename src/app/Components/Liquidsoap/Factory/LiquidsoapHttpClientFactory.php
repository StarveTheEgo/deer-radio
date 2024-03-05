<?php

declare(strict_types=1);

namespace App\Components\Liquidsoap\Factory;

use GuzzleHttp\Client as HttpClient;

class LiquidsoapHttpClientFactory
{
    /** @var string */
    private string $liquidsoapUrl;

    /**
     * @param string $liquidsoapHost
     */
    public function __construct(string $liquidsoapHost)
    {
        $this->liquidsoapUrl = $liquidsoapHost;
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
        ]);
    }
}

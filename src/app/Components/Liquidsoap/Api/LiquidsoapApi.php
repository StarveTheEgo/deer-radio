<?php

declare(strict_types=1);

namespace App\Components\Liquidsoap\Api;

use App\Components\Liquidsoap\Factory\LiquidsoapHttpClientFactory;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use LogicException;
use Psr\Http\Message\ResponseInterface;

class LiquidsoapApi
{
    /** @var string */
    private const RESPONSE_STATUS_SUCCESS = 'ok';

    private HttpClient $httpClient;

    /**
     * @param LiquidsoapHttpClientFactory $httpClientFactory
     */
    public function __construct(LiquidsoapHttpClientFactory $httpClientFactory)
    {
        $this->httpClient = $httpClientFactory->createHttpClient();
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws JsonException
     */
    public function healthcheck(): void
    {
        $this->sendApiRequestWithAssertion('GET', '/api/healthcheck');
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws JsonException
     */
    public function outputsInit(): void
    {
        $this->sendApiRequestWithAssertion('GET', '/api/outputs/init');
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws JsonException
     */
    public function outputsStop(): void
    {
        $this->sendApiRequestWithAssertion('GET', '/api/outputs/stop');
    }

    /**
     * @param string $method
     * @param string $uri
     * @return array<string, mixed>
     * @throws GuzzleException
     * @throws JsonException
     */
    private function sendApiRequest(string $method, string $uri): array
    {
        $responseData = $this->httpClient->request($method, $uri);

        return $this->parseJsonResponse($responseData);
    }

    /**
     * @param string $method
     * @param string $uri
     * @return array<string, mixed>
     * @throws GuzzleException
     * @throws JsonException
     */
    private function sendApiRequestWithAssertion(string $method, string $uri): array
    {
        $responseData = $this->sendApiRequest($method, $uri);

        $this->assertResponseStatus($responseData);

        return $responseData;
    }

    /**
     * @param ResponseInterface $response
     * @return array<string, mixed>
     * @throws JsonException
     */
    private function parseJsonResponse(ResponseInterface $response): array
    {
        $contents = (string) $response->getBody();

        return json_decode($contents,  associative: true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $response
     * @return void
     * @throws JsonException
     */
    private function assertResponseStatus(array $response): void
    {
        $status = $response['status'] ?? null;
        if ($status !== self::RESPONSE_STATUS_SUCCESS) {
            throw new LogicException(sprintf(
                'Response status is not successful: %s',
                json_encode($response, flags: JSON_THROW_ON_ERROR)
            ));
        }
    }
}

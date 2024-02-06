<?php

declare(strict_types=1);

namespace App\Components\Google\Factory;

use App\Components\Google\Model\GoogleOutputConfig;
use App\Components\Output\Entity\Output;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use Google\Client as GoogleClient;
use Illuminate\Validation\ValidationException;
use JsonException;
use Webmozart\Assert\Assert;

class GoogleClientFactory
{
    /** @var ServiceAccountReadService */
    private ServiceAccountReadService $serviceAccountReadService;

    /** @var GoogleOutputConfigFactory */
    private GoogleOutputConfigFactory $configFactory;

    /** @var array<int, GoogleClient> */
    private array $instances = [];

    /**
     * @param ServiceAccountReadService $serviceAccountReadService
     * @param GoogleOutputConfigFactory $configFactory
     */
    public function __construct(
        ServiceAccountReadService $serviceAccountReadService,
        GoogleOutputConfigFactory $configFactory
    )
    {
        $this->serviceAccountReadService = $serviceAccountReadService;
        $this->configFactory = $configFactory;
    }

    /**
     * @param Output $output
     * @return GoogleClient
     * @throws JsonException
     * @throws ValidationException
     */
    public function createForOutput(Output $output) : GoogleClient
    {
        $outputConfig = $this->configFactory->createFromArray($output->getDriverConfig());

        return $this->createFromConfig($outputConfig);
    }

    /**
     * @param GoogleOutputConfig $outputConfig
     * @return GoogleClient
     */
    private function createFromConfig(GoogleOutputConfig $outputConfig): GoogleClient
    {
        $serviceAccount = $this->serviceAccountReadService->getById($outputConfig->getServiceAccountId());
        $accessToken = $serviceAccount->getAccessToken();
        Assert::notNull($accessToken);

        $client = new GoogleClient();
        $client->setAccessToken([
            'access_token' => $accessToken->getAuthToken(),
        ]);
        $client->setAccessType('offline');
        $client->setScopes($accessToken->getScopes());

        return $client;
    }

    /**
     * Gets cached instance or creates new
     * @param Output $output
     * @return GoogleClient
     * @throws JsonException
     * @throws ValidationException
     */
    public function getOrCreateForOutput(Output $output): GoogleClient
    {
        $outputId = $output->getId();
        Assert::notNull($outputId);

        if (!array_key_exists($outputId, $this->instances)) {
            $this->instances[$outputId] = $this->createForOutput($output);
        }

        return $this->instances[$outputId];
    }
}

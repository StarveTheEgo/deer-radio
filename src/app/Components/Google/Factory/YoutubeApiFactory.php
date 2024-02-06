<?php

declare(strict_types=1);

namespace App\Components\Google\Factory;

use App\Components\Google\Api\YoutubeApi;
use App\Components\Output\Entity\Output;
use Illuminate\Validation\ValidationException;
use JsonException;
use Webmozart\Assert\Assert;

class YoutubeApiFactory
{
    private GoogleClientFactory $googleClientFactory;

    /** @var array<int, YoutubeApi> */
    private array $instances = [];

    /**
     * @param GoogleClientFactory $googleClientFactory
     */
    public function __construct(
        GoogleClientFactory $googleClientFactory
    )
    {
        $this->googleClientFactory = $googleClientFactory;
    }

    /**
     * @param Output $output
     * @return YoutubeApi
     * @throws ValidationException
     * @throws JsonException
     */
    public function createForOutput(Output $output): YoutubeApi
    {
        $googleClient = $this->googleClientFactory->getOrCreateForOutput($output);

        return new YoutubeApi($googleClient);
    }

    /**
     * Gets cached instance or creates new
     * @param Output $output
     * @return YoutubeApi
     * @throws JsonException
     * @throws ValidationException
     */
    public function getOrCreateForOutput(Output $output): YoutubeApi
    {
        $outputId = $output->getId();
        Assert::notNull($outputId);

        if (!array_key_exists($outputId, $this->instances)) {
            $this->instances[$outputId] = $this->createForOutput($output);
        }

        return $this->instances[$outputId];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Google\Service;

use App\Components\Google\Api\YoutubeApi;
use App\Components\Google\Factory\GoogleOutputConfigFactory;
use App\Components\Google\Factory\YoutubeApiFactory;
use App\Components\Google\Filler\YouTubeLiveBroadcastFiller;
use App\Components\Google\GoogleDataAccessor;
use App\Components\Output\Entity\Output;
use Google\Service\YouTube\LiveBroadcast;
use Illuminate\Validation\ValidationException;
use JsonException;
use LogicException;

class CreateOrUpdateLiveBroadcastService
{
    private GoogleDataAccessor $dataAccessor;

    private GoogleOutputConfigFactory $configFactory;

    private YouTubeLiveBroadcastFiller $liveBroadcastFiller;

    private YoutubeApiFactory $apiFactory;

    /**
     * @param GoogleDataAccessor $dataAccessor
     * @param GoogleOutputConfigFactory $configFactory
     * @param YouTubeLiveBroadcastFiller $liveBroadcastFiller
     * @param YoutubeApiFactory $apiFactory
     */
    public function __construct(
        GoogleDataAccessor         $dataAccessor,
        GoogleOutputConfigFactory  $configFactory,
        YouTubeLiveBroadcastFiller $liveBroadcastFiller,
        YoutubeApiFactory          $apiFactory
    )
    {
        $this->dataAccessor = $dataAccessor;
        $this->configFactory = $configFactory;
        $this->liveBroadcastFiller = $liveBroadcastFiller;
        $this->apiFactory = $apiFactory;
    }

    /**
     * @param Output $output
     * @param string|null $savedLiveBroadcastId
     * @return LiveBroadcast
     * @throws JsonException
     * @throws ValidationException
     */
    public function createOrUpdateLiveBroadcastForOutput(Output $output, ?string $savedLiveBroadcastId): LiveBroadcast
    {
        $outputConfig = $this->configFactory->createFromArray($output->getDriverConfig());
        $youtubeApi = $this->apiFactory->getOrCreateForOutput($output);

        $isNewBroadcast = false;
        if ($savedLiveBroadcastId !== null) {
            // try to load existing broadcast data
            $currentLiveBroadcast = $youtubeApi->findLiveBroadcastById($savedLiveBroadcastId, YoutubeApi::DEFAULT_BROADCAST_PARTS);
        } else {
            $currentLiveBroadcast = null;
        }

        if ($currentLiveBroadcast === null || !$this->isHealthyLiveBroadcast($currentLiveBroadcast)) {
            // current broadcast is not actual/healthy - create a new one
            $newBroadcast = $this->liveBroadcastFiller->fillFromConfig(new LiveBroadcast(), $outputConfig);
            $currentLiveBroadcast = $youtubeApi->createLiveBroadcast($newBroadcast, YoutubeApi::DEFAULT_BROADCAST_PARTS);

            $this->dataAccessor->setYoutubeLiveBroadcast($output, $currentLiveBroadcast);
            $isNewBroadcast = true;
        }

        if (!$isNewBroadcast) {
            // update current broadcast
            $currentLiveBroadcast = $this->liveBroadcastFiller->fillFromConfig($currentLiveBroadcast, $outputConfig);
            $youtubeApi->updateLiveBroadcast($currentLiveBroadcast, YoutubeApi::DEFAULT_BROADCAST_PARTS);
        }

        if (!$this->isHealthyLiveBroadcast($currentLiveBroadcast)) {
            throw new LogicException(sprintf(
                'Could not get healthy broadcast: %s',
                json_encode($currentLiveBroadcast->toSimpleObject())
            ));
        }

        return $currentLiveBroadcast;
    }

    /**
     * @param LiveBroadcast|null $liveBroadcast
     * @return bool
     */
    private function isHealthyLiveBroadcast(?LiveBroadcast $liveBroadcast): bool
    {
        $status = $liveBroadcast->getStatus();

        $lifecycleStatus = $status->getLifeCycleStatus();
        if (in_array($lifecycleStatus, ['complete', 'revoked'])) {
            return false;
        }

        return true;
    }
}

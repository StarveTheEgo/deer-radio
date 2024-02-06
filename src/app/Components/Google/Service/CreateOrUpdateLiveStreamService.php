<?php

declare(strict_types=1);

namespace App\Components\Google\Service;

use App\Components\Google\Api\YoutubeApi;
use App\Components\Google\Factory\YoutubeApiFactory;
use App\Components\Google\Filler\YouTubeLiveStreamFiller;
use App\Components\Google\GoogleDataAccessor;
use App\Components\Output\Entity\Output;
use Google\Service\YouTube\LiveStream;
use Illuminate\Validation\ValidationException;
use JsonException;
use LogicException;

class CreateOrUpdateLiveStreamService
{
    /** @var YouTubeLiveStreamFiller */
    private YouTubeLiveStreamFiller $liveStreamFiller;

    /** @var YoutubeApiFactory */
    private YoutubeApiFactory $apiFactory;

    /**
     * @param YouTubeLiveStreamFiller $liveStreamFiller
     * @param YoutubeApiFactory $apiFactory
     */
    public function __construct(
        YouTubeLiveStreamFiller $liveStreamFiller,
        YoutubeApiFactory $apiFactory
    )
    {
        $this->liveStreamFiller = $liveStreamFiller;
        $this->apiFactory = $apiFactory;
    }

    /**
     * @param Output $output
     * @param string|null $savedLiveStreamId
     * @return LiveStream
     * @throws JsonException
     * @throws ValidationException
     */
    public function createOrUpdateLiveStreamForOutput(Output $output, ?string $savedLiveStreamId): LiveStream
    {
        $youtubeApi = $this->apiFactory->getOrCreateForOutput($output);

        $isNewLiveStream = false;
        if ($savedLiveStreamId !== null) {
            // try to reload existing LiveStream data
            $currentLiveStream = $youtubeApi->getLiveStreamById($savedLiveStreamId, YoutubeApi::DEFAULT_LIVESTREAM_PARTS);
        } else {
            $currentLiveStream = null;
        }

        if ($currentLiveStream === null || !$this->isHealthyLiveStream($currentLiveStream)) {
            // current LiveStream is not actual/healthy - creating new one
            $newLiveStream = $this->liveStreamFiller->fill(new LiveStream());
            $currentLiveStream = $youtubeApi->createLiveStream($newLiveStream, YoutubeApi::DEFAULT_LIVESTREAM_PARTS);
            $isNewLiveStream = true;
        }

        if (!$isNewLiveStream) {
            // update current LiveStream
            $currentLiveStream = $this->liveStreamFiller->fill($currentLiveStream);
            $youtubeApi->updateLiveStream($currentLiveStream, YoutubeApi::DEFAULT_LIVESTREAM_PARTS);
        }

        if (!$this->isHealthyLiveStream($currentLiveStream)) {
            throw new LogicException(sprintf(
                'Could not get healthy livestream: %s',
                json_encode($currentLiveStream->toSimpleObject())
            ));
        }

        return $currentLiveStream;
    }

    /**
     * @param LiveStream|null $liveStream
     * @return bool
     */
    private function isHealthyLiveStream(?LiveStream $liveStream): bool
    {
        $status = $liveStream->getStatus();

        $streamStatus = $status->getStreamStatus();
        if ($streamStatus === 'error') {
            return false;
        }

        $healthStatus = $status->getHealthStatus();
        if ($healthStatus->getStatus() === 'bad') {
            return false;
        }

        return true;
    }
}

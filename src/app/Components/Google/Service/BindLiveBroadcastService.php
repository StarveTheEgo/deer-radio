<?php

declare(strict_types=1);

namespace App\Components\Google\Service;

use App\Components\Google\Factory\YoutubeApiFactory;
use App\Components\Output\Entity\Output;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveStream;
use Illuminate\Validation\ValidationException;
use JsonException;

class BindLiveBroadcastService
{
    private YoutubeApiFactory $apiFactory;

    /**
     * @param YoutubeApiFactory $apiFactory
     */
    public function __construct(
        YoutubeApiFactory $apiFactory
    )
    {
        $this->apiFactory = $apiFactory;
    }

    /**
     * @param Output $output
     * @param LiveBroadcast $liveBroadcast
     * @param LiveStream $liveStream
     * @param array<string> $parts
     * @return LiveStream|null
     * @throws JsonException
     * @throws ValidationException
     */
    public function bindBroadcastToLiveStream(Output $output, LiveBroadcast $liveBroadcast, LiveStream $liveStream, array $parts): ?LiveBroadcast
    {
        $youtubeApi = $this->apiFactory->getOrCreateForOutput($output);

        return $youtubeApi->bindLiveBroadcastToLiveStream($liveBroadcast, $liveStream, $parts);
    }
}

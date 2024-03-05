<?php

declare(strict_types=1);

namespace App\Components\Google\Api;

use Google\Client as GoogleClient;
use Google\Service\YouTube as YoutubeService;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveChatMessage;
use Google\Service\YouTube\LiveStream;
use Webmozart\Assert\Assert;

class YoutubeApi
{
    /** @var array<string> List of YouTube LiveStream API parts for broadcasts */
    public const DEFAULT_LIVESTREAM_PARTS = [
        'snippet',
        'cdn',
        'contentDetails',
        'status',
    ];

    /** @var array<string> List of YouTube LiveBroadCast API parts for broadcasts */
    public const DEFAULT_BROADCAST_PARTS = [
        'contentDetails',
        'status',
        'snippet'
    ];

    private YoutubeService $youtubeService;

    public function __construct(GoogleClient $googleClient)
    {
        $this->youtubeService = new YoutubeService($googleClient);
    }

    /**
     * @param string $id
     * @param array<string> $parts
     * @return LiveStream|null
     */
    public function getLiveStreamById(string $id, array $parts): ?LiveStream
    {
        $liveStreams = $this->youtubeService->liveStreams->listLiveStreams(
            implode(',', $parts),
            ['id' => $id]
        )->getItems();

        if (empty($liveStreams)) {
            return null;
        }

        Assert::count($liveStreams, 1);

        return $liveStreams[array_key_first($liveStreams)];
    }

    /**
     * @param LiveStream $liveStream
     * @param array<string> $parts
     * @return LiveStream
     */
    public function createLiveStream(LiveStream $liveStream, array $parts) : LiveStream
    {
        return $this->youtubeService->liveStreams->insert(implode(',', $parts), $liveStream);
    }

    /**
     * @param LiveStream $liveStream
     * @param array<string> $parts
     * @return LiveStream
     */
    public function updateLiveStream(LiveStream $liveStream, array $parts) : LiveStream
    {
        return $this->youtubeService->liveStreams->update(implode(',', $parts), $liveStream);
    }

    /**
     * @param string $id
     * @param array<string> $parts
     * @return LiveBroadcast|null
     */
    public function findLiveBroadcastById(string $id, array $parts): ?LiveBroadcast
    {
        $liveBroadcasts = $this->youtubeService->liveBroadcasts->listLiveBroadcasts(
            implode(',', $parts),
            ['id' => $id]
        )->getItems();

        if (empty($liveBroadcasts)) {
            return null;
        }

        Assert::count($liveBroadcasts, 1);

        return $liveBroadcasts[array_key_first($liveBroadcasts)];
    }

    /**
     * @param LiveBroadcast $liveBroadcast
     * @param array $parts
     * @return LiveBroadcast
     */
    public function createLiveBroadcast(LiveBroadcast $liveBroadcast, array $parts): LiveBroadcast
    {
        return $this->youtubeService->liveBroadcasts->insert(implode(',', $parts), $liveBroadcast);
    }

    /**
     * @param LiveBroadcast $liveBroadcast
     * @param array<string> $parts
     * @return LiveBroadcast
     */
    public function updateLiveBroadcast(LiveBroadcast $liveBroadcast, array $parts) : LiveBroadcast
    {
        return $this->youtubeService->liveBroadcasts->update(implode(',', $parts), $liveBroadcast);
    }

    /**
     * @param LiveBroadcast $liveBroadcast
     * @param LiveStream $liveStream
     * @param array $parts
     * @return LiveBroadcast
     */
    public function bindLiveBroadcastToLiveStream(LiveBroadcast $liveBroadcast, LiveStream $liveStream, array $parts): LiveBroadcast
    {
        return $this->youtubeService->liveBroadcasts->bind($liveBroadcast->getId(), implode(',', $parts), [
            'streamId' => $liveStream->getId(),
        ]);
    }

    /**
     * @param LiveChatMessage $message
     * @return LiveChatMessage
     */
    public function createLiveChatMessage(LiveChatMessage $message) : LiveChatMessage
    {
        return $this->youtubeService->liveChatMessages->insert('snippet', $message);
    }
}

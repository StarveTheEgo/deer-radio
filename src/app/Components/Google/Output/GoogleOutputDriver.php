<?php

declare(strict_types=1);

namespace App\Components\Google\Output;

use App\Components\Google\Api\YoutubeApi;
use App\Components\Google\GoogleDataAccessor;
use App\Components\Google\Service\BindLiveBroadcastService;
use App\Components\Google\Service\CreateOrUpdateLiveBroadcastService;
use App\Components\Google\Service\CreateOrUpdateLiveStreamService;
use App\Components\Google\Service\ReadLiveBroadcastService;
use App\Components\Output\Entity\Output;
use App\Components\Output\Enum\OutputStreamState;
use App\Components\Output\Interfaces\ChatClientAwareInterface;
use App\Components\Output\Interfaces\OutputDriverInterface;
use Google\Service\YouTube\LiveStream;
use Illuminate\Validation\ValidationException;
use JsonException;
use Webmozart\Assert\Assert;

class GoogleOutputDriver implements OutputDriverInterface, ChatClientAwareInterface
{
    private CreateOrUpdateLiveStreamService $createOrUpdateLiveStreamService;

    private CreateOrUpdateLiveBroadcastService $createOrUpdateLiveBroadcastService;

    private ReadLiveBroadcastService $readLiveBroadcastService;

    private BindLiveBroadcastService $bindLiveBroadcastService;

    private GoogleDataAccessor $dataAccessor;

    /**
     * @return string
     */
    public static function getTechnicalName(): string
    {
        return 'youtube';
    }

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return 'YouTube';
    }

    /**
     * @return string
     */
    public static function getChatClientClassName(): string
    {
        return GoogleChatClient::class;
    }

    /**
     * @param CreateOrUpdateLiveStreamService $createOrUpdateLiveStreamService
     * @param CreateOrUpdateLiveBroadcastService $createOrUpdateLiveBroadcastService
     * @param ReadLiveBroadcastService $readLiveBroadcastService
     * @param BindLiveBroadcastService $bindLiveBroadcastService
     * @param GoogleDataAccessor $dataAccessor
     */
    public function __construct(
        CreateOrUpdateLiveStreamService $createOrUpdateLiveStreamService,
        CreateOrUpdateLiveBroadcastService $createOrUpdateLiveBroadcastService,
        ReadLiveBroadcastService $readLiveBroadcastService,
        BindLiveBroadcastService $bindLiveBroadcastService,
        GoogleDataAccessor $dataAccessor
    )
    {
        $this->createOrUpdateLiveStreamService = $createOrUpdateLiveStreamService;
        $this->createOrUpdateLiveBroadcastService = $createOrUpdateLiveBroadcastService;
        $this->readLiveBroadcastService = $readLiveBroadcastService;
        $this->bindLiveBroadcastService = $bindLiveBroadcastService;
        $this->dataAccessor = $dataAccessor;
    }

    /**
     * @param Output $output
     * @return void
     * @throws JsonException
     * @throws ValidationException
     */
    public function prepareLiveStream(Output $output): void
    {
        // actualize current livestream
        $savedLiveStream = $this->dataAccessor->getYoutubeLiveStream($output);
        $liveStream = $this->createOrUpdateLiveStreamService->createOrUpdateLiveStreamForOutput($output, $savedLiveStream?->getId());

        // actualize current broadcast
        $savedLiveBroadcast = $this->dataAccessor->getYoutubeLiveBroadcast($output);
        $liveBroadcast = $this->createOrUpdateLiveBroadcastService->createOrUpdateLiveBroadcastForOutput($output, $savedLiveBroadcast?->getId());

        if ($liveBroadcast->getContentDetails()->getBoundStreamId() === null) {
            // need to bind broadcast to current livestream
            $liveBroadcast = $this->bindLiveBroadcastService->bindBroadcastToLiveStream(
                $output,
                $liveBroadcast,
                $liveStream,
                YoutubeApi::DEFAULT_BROADCAST_PARTS
            );
        }

        // store livestream and broadcast for future usage
        $this->dataAccessor->setYoutubeLiveStream($output, $liveStream);
        $this->dataAccessor->setYoutubeLiveBroadcast($output, $liveBroadcast);
    }

    /**
     * @param Output $output
     * @return array{
     *      rtmpUrl: string,
     * }
     */
    public function getLiquidsoapPayload(Output $output): array
    {
        $liveStream = $this->dataAccessor->getYoutubeLiveStream($output);
        Assert::notNull($liveStream);

        return [
            'rtmpUrl' => $this->buildRtmpUrl($liveStream),
        ];
    }

    /**
     * Builds RTMP streaming URL for specified livestream
     * @param LiveStream $liveStream
     * @return string
     */
    private function buildRtmpUrl(LiveStream $liveStream): string
    {
        $ingestionInfo = $liveStream->getCdn()->getIngestionInfo();

        return $ingestionInfo->getIngestionAddress().'/'.$ingestionInfo->getStreamName();
    }

    /**
     * @param Output $output
     * @return OutputStreamState
     * @throws JsonException
     * @throws ValidationException
     */
    public function getStreamState(Output $output) : OutputStreamState
    {
        $savedLiveBroadcast = $this->dataAccessor->getYoutubeLiveBroadcast($output);
        if ($savedLiveBroadcast === null) {
            return OutputStreamState::NOT_READY;
        }

        $liveBroadcast = $this
            ->readLiveBroadcastService
            ->findLiveBroadcastById($output, $savedLiveBroadcast->getId(), ['status']);

        $lifeCycleStatus = $liveBroadcast->getStatus()->getLifeCycleStatus();

        // remap the LifecycleStatus to StreamState
        return match ($lifeCycleStatus) {
            'complete', 'revoked' => OutputStreamState::FINISHED,
            'created', 'ready', 'testStarting', 'liveStarting' => OutputStreamState::CREATED,
            'live', 'testing' => OutputStreamState::LIVE,
            default => OutputStreamState::UNKNOWN,
        };
    }
}

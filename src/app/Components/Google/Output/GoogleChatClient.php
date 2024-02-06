<?php

declare(strict_types=1);

namespace App\Components\Google\Output;

use App\Components\Google\Factory\GoogleOutputConfigFactory;
use App\Components\Google\Factory\YoutubeApiFactory;
use App\Components\Google\GoogleDataAccessor;
use App\Components\Output\Entity\Output;
use App\Components\Output\Interfaces\ChatClientInterface;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveChatMessage;
use Google\Service\YouTube\LiveChatMessageSnippet;
use Google\Service\YouTube\LiveChatTextMessageDetails;
use Illuminate\Validation\ValidationException;
use JsonException;
use LogicException;
use Webmozart\Assert\Assert;

class GoogleChatClient implements ChatClientInterface
{
    /** @var GoogleDataAccessor */
    private GoogleDataAccessor $dataAccessor;

    /** @var GoogleOutputConfigFactory */
    private GoogleOutputConfigFactory $configFactory;

    /** @var YoutubeApiFactory */
    private YoutubeApiFactory $youtubeApiFactory;

    /**
     * @param GoogleDataAccessor $dataAccessor
     * @param GoogleOutputConfigFactory $configFactory
     * @param YoutubeApiFactory $youtubeApiFactory
     */
    public function __construct(
        GoogleDataAccessor $dataAccessor,
        GoogleOutputConfigFactory $configFactory,
        YoutubeApiFactory $youtubeApiFactory
    )
    {
        $this->dataAccessor = $dataAccessor;
        $this->configFactory = $configFactory;
        $this->youtubeApiFactory = $youtubeApiFactory;
    }

    /**
     * @param Output $output
     * @param string $messageText
     * @param string|null $channel
     * @return void
     * @throws ValidationException
     * @throws JsonException
     */
    public function sendMessage(Output $output, string $messageText, string $channel = null) : void
    {
        if (empty($messageText)) {
            throw new LogicException('Message is empty');
        }

        $outputConfig = $this->configFactory->createFromArray($output->getDriverConfig());
        if (false === $outputConfig->getChatEnabled()) {
            return;
        }

        $liveBroadcast = $this->dataAccessor->getYoutubeLiveBroadcast($output);
        Assert::notNull($liveBroadcast);

        $youtubeApi = $this->youtubeApiFactory->getOrCreateForOutput($output);

        $liveChatMessage = $this->buildLiveChatMessage($liveBroadcast, $messageText);
        $youtubeApi->createLiveChatMessage($liveChatMessage);
    }

    /**
     * @param LiveBroadcast $liveBroadcast
     * @param string $messageText
     * @return LiveChatMessage
     */
    private function buildLiveChatMessage(
        LiveBroadcast $liveBroadcast,
        string $messageText
    ): LiveChatMessage
    {
        $details = new LiveChatTextMessageDetails();
        $details->setMessageText($messageText);

        $snippet = new LiveChatMessageSnippet();
        $snippet->setLiveChatId($liveBroadcast->getSnippet()->getLiveChatId());
        $snippet->setType('textMessageEvent');
        $snippet->setTextMessageDetails($details);

        $liveChatMessage = new LiveChatMessage();
        $liveChatMessage->setSnippet($snippet);

        return $liveChatMessage;
    }
}

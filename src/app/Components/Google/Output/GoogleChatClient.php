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
    /** @var int Limit of characters per single LiveChatMessage */
    public const MESSAGE_LENGTH_LIMIT = 180;

    /** @var string Delimiter between message parts */
    private const MESSAGE_PARTS_DELIMITER = ' ... ';

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

        $formattedMessages = $this->splitIntoFormattedChatMessages($messageText);
        Assert::minCount($formattedMessages, 1);

        foreach ($formattedMessages as $formattedMessage) {
            $liveChatMessage = $this->buildLiveChatMessage($liveBroadcast, $formattedMessage);
            $youtubeApi->createLiveChatMessage($liveChatMessage);
        }
    }

    /**
     * Splits specified message into list of formatted YouTube LiveChatMessages
     * @param string $message
     * @return array<string>
     */
    public function splitIntoFormattedChatMessages(string $message) : array
    {
        $message = str_replace("\n", '. ', $message);

        $lengthLimit = self::MESSAGE_LENGTH_LIMIT;
        $delimiter = self::MESSAGE_PARTS_DELIMITER;

        $extraLength =  mb_strlen($message) - $lengthLimit;
        if ($extraLength > 0) {
            // the text length exceeds the limit, let's split it into strings of specified limit
            $wordWrappedText = $this->multibyteWordWrap($message, $lengthLimit);
            // split word wrapped text by lines
            $messageParts = array_filter(explode("\n", $wordWrappedText));
        } else {
            $messageParts = [$message];
        }

        // add visual delimiters between parts
        $partsCount = count($messageParts);
        for ($i = 0; $i < $partsCount; $i++) {
            if ($i === 0 && $partsCount > 1) {
                // this is the first part with upcoming parts
                $messageParts[$i] .= $delimiter;
            } elseif ($i > 0) {
                // any next part should be prefixed by delimiter as well
                $messageParts[$i] = $delimiter.$messageParts[$i];
            }
        }

        return $messageParts;
    }

    /**
     * @param string $string
     * @param int $width
     * @param string $break
     * @return string
     */
    private function multibyteWordWrap(string $string, int $width = 75, string $break = "\n"): string
    {
        // Match anything 1 to $width chars long followed by whitespace or EOS,
        // otherwise match anything $width chars long
        $search = '/(.{1,'.$width.'})(?:\s|$)|(.{'.$width.'})/uS';
        $replace = '$1$2'.$break;

        /** @var string $wordWrappedText */
        $wordWrappedText = preg_replace($search, $replace, $string);

        return $wordWrappedText;
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

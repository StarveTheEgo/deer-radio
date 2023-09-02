<?php

declare(strict_types=1);

namespace App\Components\Google\Output;

use App\Components\Output\Interfaces\ChatClientInterface;
use Google_Service_YouTube_LiveBroadcast;
use Google_Service_YouTube_LiveChatMessage;
use Google_Service_YouTube_LiveChatMessageSnippet;
use Google_Service_YouTube_LiveChatTextMessageDetails;
use LogicException;

class GoogleChatClient implements ChatClientInterface
{

    public function __construct()
    {

    }

    public function sendMessage(string $messageText, string $channel = null) : void
    {
        if (empty($messageText)) {
            throw new LogicException('Message is empty');
        }

        $liveBroadcast = $this->getLiveBroadcast();
        if ($liveBroadcast === null) {
            throw new LogicException('Could not get live broadcast');
        }

        $liveChatMessage = $this->buildLiveChatMessage($liveBroadcast, $messageText);

        try {
            $this->getYoutubeService()->liveChatMessages->insert('snippet', $messageText);
        } catch (Exception $ex) {
            if (strpos($ex->getMessage(), 'no longer live') !== false) {
                // the stored broadcast is not actual anymore
                // @todo attempts probably, because we are missing one message in the chat now :/
                $this->storeLiveBroadcast(null);
            }
            throw $ex;
        }
    }

    /**
     * @param Google_Service_YouTube_LiveBroadcast $liveBroadcast
     * @param string $messageText
     * @return Google_Service_YouTube_LiveChatMessage
     */
    private function buildLiveChatMessage(
        Google_Service_YouTube_LiveBroadcast $liveBroadcast,
        string $messageText
    ): Google_Service_YouTube_LiveChatMessage
    {
        $liveChatTextMessageDetails = new Google_Service_YouTube_LiveChatTextMessageDetails();
        $liveChatTextMessageDetails->setMessageText($messageText);

        $liveChatMessageSnippet = new Google_Service_YouTube_LiveChatMessageSnippet();
        $liveChatMessageSnippet->setLiveChatId($liveBroadcast->snippet->liveChatId);
        $liveChatMessageSnippet->setType('textMessageEvent');
        $liveChatMessageSnippet->setTextMessageDetails($liveChatTextMessageDetails);

        $liveChatMessage = new Google_Service_YouTube_LiveChatMessage();
        $liveChatMessage->setSnippet($liveChatMessageSnippet);

        return $liveChatMessage;
    }
}

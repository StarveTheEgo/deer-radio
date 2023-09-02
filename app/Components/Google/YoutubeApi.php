<?php

declare(strict_types=1);

namespace App\Components\Google;

use App\Components\Setting\Service\SettingServiceRegistry;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_LiveBroadcast;
use Google_Service_YouTube_LiveChatMessage;
use Google_Service_YouTube_LiveChatMessageSnippet;
use Google_Service_YouTube_LiveChatTextMessageDetails;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Exception;

class YoutubeApi
{
    private const TITLE_SUBSTRING = 'deer radio 24/7'; // @fixme hardcode
    private const CACHE_KEY = 'youtube.liveBroadcast';

    public const DEFAULT_TITLE = 'Deer Radio 24/7: find your favorite music while having unique audio visual deer experience!';

    /** @var Google_Client */
    private $client;
    /** @var Google_Service_YouTube */
    private $youtubeService;

    private $liveBroadcast;
    private $accessToken;
    private $refreshToken;
    private SettingServiceRegistry $settingServiceRegistry;

    public function __construct(SettingServiceRegistry $settingServiceRegistry) {

        $this->accessToken = get_radio_setting('youtube.access_token', '');
        $this->refreshToken = get_radio_setting('youtube.refresh_token', '');
        $this->settingServiceRegistry = $settingServiceRegistry;
    }

    public function getStreamTitle() : string {
        $title = (string) get_radio_setting('deer-radio.stream_title', self::DEFAULT_TITLE);
        if (empty($title)) {
            throw new LogicException('Empty Deer Radio title');
        }
        return $title;
    }

    private function getClient() : Google_Client {
        if ($this->client === null) {
            $client = new Google_Client();
            $client->setAccessToken($this->accessToken);
            $client->setAccessType("offline");
            $client->setAuthConfig(Storage::disk('private')->path('youtube-oauth.json'));
            $client->addScope(Google_Service_YouTube::YOUTUBE);
            $this->client = $client;
        }
        return $this->client;
    }

    public function refreshToken()
    {
        $client = $this->getClient();
        $token_data = $client->refreshToken($this->refreshToken);
        if (empty($token_data['access_token'])) {
            throw new LogicException(sprintf('Could not refresh access token, got: %s', var_export($token_data, true)));
        }
        $access_token = $client->getAccessToken()['access_token'] ?? '';
        if (empty($access_token)) {
            throw new LogicException('Empty access token');
        }
        $refresh_token = $client->getRefreshToken();
        if (empty($refresh_token)) {
            throw new LogicException('Empty refresh token');
        }
        $this->accessToken = $access_token;
        $this->refreshToken = $refresh_token;
        set_radio_setting('youtube.access_token', $access_token);
        set_radio_setting('youtube.refresh_token', $refresh_token);
    }

    public function getYoutubeServicePublic() : Google_Service_YouTube {
        return $this->getYoutubeService();
    }

    private function getYoutubeService() : Google_Service_YouTube {
        if ($this->youtubeService === null) {
            return new Google_Service_YouTube($this->getClient());
        }
        return $this->youtubeService;
    }

    public function getDeerRadioLivestreams() {
        // find the Deer Radio stream !
        $streams = $this->getYoutubeService()->liveStreams->listLiveStreams('snippet,cdn,contentDetails,status', [
            'mine' => true
        ])->getItems();

        $deer_radio_title = $this->getStreamTitle();
        foreach ($streams as $index => $livestream) {
            if ($livestream->snippet->title !== $deer_radio_title) {
                unset($streams[$index]);
            }
        }
        return $streams;
    }



    public function sendMessage(string $message_text) : void {
        if (empty($message_text)) {
            throw new LogicException('Empty message text');
        }
        $live_broadcast = $this->getLiveBroadcast();
        if ($live_broadcast === null) {
            throw new LogicException('Could not get live broadcast');
        }

        $message_details = new Google_Service_YouTube_LiveChatTextMessageDetails();
        $message_details->setMessageText($message_text);

        $snippet = new Google_Service_YouTube_LiveChatMessageSnippet();
        $snippet->setLiveChatId($live_broadcast->snippet->liveChatId);
        $snippet->setType('textMessageEvent');
        $snippet->setTextMessageDetails($message_details);
        $message = new Google_Service_YouTube_LiveChatMessage();
        $message->setSnippet($snippet);
        try {
            $this->getYoutubeService()->liveChatMessages->insert('snippet', $message);
        } catch (Exception $ex) {
            if (strpos($ex->getMessage(), 'no longer live') !== false) {
                // the stored broadcast is not actual anymore
                // @todo attempts probably, because we are missing one message in the chat now :/
                $this->storeLiveBroadcast(null);
            }
            throw $ex;
        }
    }

    public function getMessageLengthLimit() :int {
        return 180;
    }
}

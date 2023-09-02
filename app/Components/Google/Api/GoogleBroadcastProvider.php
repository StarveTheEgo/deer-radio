<?php

declare(strict_types=1);

namespace App\Components\Google\Api;

use App\Components\Google\Enum\GoogleDataKey;
use App\Components\Google\GoogleDataAccessor;
use Google_Service_YouTube_LiveBroadcast;
use Psr\Log\LoggerInterface;

class GoogleBroadcastProvider
{
    private GoogleDataAccessor $dataAccessor;

    private ?Google_Service_YouTube_LiveBroadcast $liveBroadcast = null;
    private LoggerInterface $logger;

    public function __construct(
        GoogleDataAccessor $dataAccessor,
        LoggerInterface $logger
    )
    {
        $this->dataAccessor = $dataAccessor;
        $this->logger = $logger;
    }

    public function getLiveBroadcast() : ?Google_Service_YouTube_LiveBroadcast
    {
        if ($this->liveBroadcast === null) {
            $this->liveBroadcast = $this->restoreLiveBroadcast();
            if ($this->liveBroadcast === null) {
                $this->liveBroadcast = $this->fetchLiveBroadcast();
                $this->storeLiveBroadcast($this->liveBroadcast);
            }
        }
        return $this->liveBroadcast;
    }

    private function restoreLiveBroadcast() : ?Google_Service_YouTube_LiveBroadcast
    {
        $liveBroadcast = $this->dataAccessor->getValue(GoogleDataKey::STORED_LIVE_BROADCAST->value);
        if ($liveBroadcast === null) {
            return null;
        }

        if (!($liveBroadcast instanceof Google_Service_YouTube_LiveBroadcast)) {
            // @fixme side effect
            $this->logger->error('Invalid broadcast stored in the component');
            $this->storeLiveBroadcast(null);
        }

        return $liveBroadcast;
    }

    private function fetchLiveBroadcast() : ?Google_Service_YouTube_LiveBroadcast {
        // find the Deer Radio stream !
        foreach ($this->getDeerRadioBroadcasts('active') as $broadcast) {
            // @todo check if we can use a livestream without 'live' status yet
            // @todo sсheduled broadcast
            if ($broadcast->status->lifeCycleStatus === 'live') {
                return $broadcast;
            }
        }
        return null;
    }

    public function getDeerRadioBroadcasts(string $status) : array {
        // find the Deer Radio stream !
        $broadcasts = $this->getYoutubeService()->liveBroadcasts->listLiveBroadcasts('status,snippet', [
            'broadcastStatus' => $status,
        ])->getItems();

        $deer_radio_title = $this->getStreamTitle();
        foreach ($broadcasts as $index => $broadcast) {
            if ($broadcast->snippet->title !== $deer_radio_title) {
                unset($broadcasts[$index]);
            }
            // if (mb_strpos(mb_strtolower($broadcast->snippet->title), self::TITLE_SUBSTRING) === false) {
            //     unset($broadcasts[$index]);
            // }
        }
        return $broadcasts;
    }

    public function storeLiveBroadcast(?Google_Service_YouTube_LiveBroadcast $broadcast) : void {
        $this->dataAccessor->setValue(GoogleDataKey::STORED_LIVE_BROADCAST->value, $broadcast);
    }
}

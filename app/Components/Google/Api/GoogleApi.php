<?php

declare(strict_types=1);

namespace App\Components\Google\Api;

use Google_Service_YouTube_LiveBroadcast;

class GoogleApi
{
    public function __construct()
    {

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
}

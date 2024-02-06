<?php

declare(strict_types=1);

namespace App\Components\Google\Model;

use App\Components\Google\Enum\LiveBroadcastPrivacyStatus;

class GoogleOutputConfig
{
    /** @var int */
    private int $serviceAccountId;

    /** @var bool */
    private bool $chatEnabled;

    /** @var LiveBroadcastPrivacyStatus */
    private LiveBroadcastPrivacyStatus $privacyStatus;

    /**
     * @return int
     */
    public function getServiceAccountId(): int
    {
        return $this->serviceAccountId;
    }

    /**
     * @param int $serviceAccountId
     * @return GoogleOutputConfig
     */
    public function setServiceAccountId(int $serviceAccountId): GoogleOutputConfig
    {
        $this->serviceAccountId = $serviceAccountId;
        return $this;
    }

    /**
     * @return bool
     */
    public function getChatEnabled(): bool
    {
        return $this->chatEnabled;
    }

    /**
     * @param bool $chatEnabled
     * @return GoogleOutputConfig
     */
    public function setChatEnabled(bool $chatEnabled): GoogleOutputConfig
    {
        $this->chatEnabled = $chatEnabled;
        return $this;
    }

    /**
     * @return LiveBroadcastPrivacyStatus
     */
    public function getPrivacyStatus(): LiveBroadcastPrivacyStatus
    {
        return $this->privacyStatus;
    }

    /**
     * @param LiveBroadcastPrivacyStatus $privacyStatus
     * @return GoogleOutputConfig
     */
    public function setPrivacyStatus(LiveBroadcastPrivacyStatus $privacyStatus): GoogleOutputConfig
    {
        $this->privacyStatus = $privacyStatus;
        return $this;
    }
}

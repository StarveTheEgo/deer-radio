<?php

declare(strict_types=1);

namespace App\Components\Google\Filler;

use App\Components\DeerRadio\Enum\DeerRadioSettingKey;
use App\Components\Google\Model\GoogleOutputConfig;
use App\Components\Setting\Service\SettingReadService;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveBroadcastContentDetails;
use Google\Service\YouTube\LiveBroadcastSnippet;
use Google\Service\YouTube\LiveBroadcastStatus;

class YouTubeLiveBroadcastFiller
{
    private SettingReadService $settingReadService;

    /**
     * @param SettingReadService $settingReadService
     */
    public function __construct(
        SettingReadService $settingReadService
    )
    {
        $this->settingReadService = $settingReadService;
    }

    /**
     * @param LiveBroadcast $liveBroadcast
     * @param GoogleOutputConfig $outputConfig
     * @return LiveBroadcast
     */
    public function fillFromConfig(LiveBroadcast $liveBroadcast, GoogleOutputConfig $outputConfig): LiveBroadcast
    {
        // content details
        $contentDetails = $liveBroadcast->getContentDetails() ?? (new LiveBroadcastContentDetails());
        $contentDetails->setEnableLowLatency(true);
        $contentDetails->setEnableAutoStart(true);
        $contentDetails->setEnableAutoStop(false);
        $contentDetails->setEnableEmbed(false);
        $liveBroadcast->setContentDetails($contentDetails);

        // snippet
        $snippet = $liveBroadcast->getSnippet() ?? (new LiveBroadcastSnippet());
        $startTime = $this->makeAtomString(time() - 10);
        $snippet->setScheduledStartTime($startTime);
        $snippet->setTitle($this->settingReadService->getValue(DeerRadioSettingKey::TITLE->value));
        $snippet->setDescription($this->settingReadService->getValue(DeerRadioSettingKey::DESCRIPTION->value));
        $liveBroadcast->setSnippet($snippet);

        // status
        $status = $liveBroadcast->getStatus() ?? (new LiveBroadcastStatus());
        $status->setPrivacyStatus($outputConfig->getPrivacyStatus()->value);
        $liveBroadcast->setStatus($status);

        return $liveBroadcast;
    }

    /**
     * @param int $timestamp
     * @return string
     */
    private function makeAtomString(int $timestamp) : string {
        return date('c', $timestamp);
    }
}

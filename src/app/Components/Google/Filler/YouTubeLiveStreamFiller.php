<?php

declare(strict_types=1);

namespace App\Components\Google\Filler;

use App\Components\DeerRadio\Enum\DeerRadioSettingKey;
use App\Components\Setting\Service\SettingReadService;
use Google\Service\YouTube\CdnSettings;
use Google\Service\YouTube\LiveStream;
use Google\Service\YouTube\LiveStreamSnippet;

class YouTubeLiveStreamFiller
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
     * @param LiveStream $liveStream
     * @return LiveStream
     */
    public function fill(LiveStream $liveStream): LiveStream
    {
        // snippet
        $snippet = $liveStream->getSnippet() ?? (new LiveStreamSnippet());

        $snippet->setTitle($this->settingReadService->getValue(DeerRadioSettingKey::TITLE->value));
        $snippet->setDescription($this->settingReadService->getValue(DeerRadioSettingKey::DESCRIPTION->value));
        $liveStream->setSnippet($snippet);

        // cdn settings
        $cdn = $liveStream->getCdn() ?? (new CdnSettings());

        $cdn->setResolution('variable');
        $cdn->setFrameRate('variable');
        $cdn->setIngestionType('rtmp');
        $liveStream->setCdn($cdn);

        $liveStream->setKind('youtube#liveStream');

        return $liveStream;
    }
}

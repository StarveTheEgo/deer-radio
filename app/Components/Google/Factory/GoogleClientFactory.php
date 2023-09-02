<?php

declare(strict_types=1);

namespace App\Components\Google\Factory;

use App\Components\Google\Enum\GoogleOutputSettingKey;
use App\Components\Setting\Service\SettingServiceRegistry;
use Google_Client;
use Google_Service_YouTube;

class GoogleClientFactory
{
    private SettingServiceRegistry $settingServiceRegistry;

    public function __construct(
        SettingServiceRegistry $settingServiceRegistry
    )
    {
        $this->settingServiceRegistry = $settingServiceRegistry;
    }

    public function createClientFromDefaultSettings() : Google_Client
    {
        $settingReadService = $this->settingServiceRegistry->getReadService();

        $authConfig = $settingReadService->getValue(GoogleOutputSettingKey::AUTH_CONFIG->value);
        $accessToken = $settingReadService->getValue(GoogleOutputSettingKey::ACCESS_TOKEN->value);

        $client = new Google_Client();
        $client->setAccessToken($accessToken);
        $client->setAccessType('offline');
        $client->setAuthConfig($authConfig);
        $client->addScope(Google_Service_YouTube::YOUTUBE);

        return $client;
    }
}

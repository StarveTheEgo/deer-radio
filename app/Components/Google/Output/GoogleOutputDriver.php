<?php

declare(strict_types=1);

namespace App\Components\Google\Output;

use App\Components\Google\Enum\GoogleOutputSettingKey;
use App\Components\Output\Interfaces\ChatClientAwareInterface;
use App\Components\Output\Interfaces\OutputDriverInterface;
use App\Components\Setting\Service\SettingServiceRegistry;

class GoogleOutputDriver implements OutputDriverInterface, ChatClientAwareInterface
{
    private GoogleChatClient $chatClient;

    private SettingServiceRegistry $settingServiceRegistry;

    public static function getTechnicalName(): string
    {
        return 'youtube';
    }

    public static function getTitle(): string
    {
        return 'YouTube';
    }

    public function __construct(
        GoogleChatClient $chatClient,
        SettingServiceRegistry $settingServiceRegistry
    )
    {
        $this->chatClient = $chatClient;
        $this->settingServiceRegistry = $settingServiceRegistry;
    }

    public function getChatClient(): GoogleChatClient
    {
        return $this->chatClient;
    }

    public function getLiquidsoapPayload(): array
    {
        $settingReadService = $this->settingServiceRegistry->getReadService();

        return [
            'endpoint' => $settingReadService->getValue(GoogleOutputSettingKey::ENDPOINT->value),
            'api_key' => $settingReadService->getValue(GoogleOutputSettingKey::API_KEY->value),
        ];
    }
}

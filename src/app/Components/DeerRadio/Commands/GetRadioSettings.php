<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Commands;

use App\Components\DeerRadio\Enum\LivestreamSettingKey;
use App\Components\Setting\Service\SettingServiceRegistry;
use Illuminate\Console\Command;
use JsonException;

class GetRadioSettings extends Command {

    protected $signature = 'radio-settings:get';
    protected $description = 'Returns radio settings in JSON format';
    private SettingServiceRegistry $settingServiceRegistry;

    public function __construct(
        SettingServiceRegistry $settingServiceRegistry
    )
    {
        parent::__construct();
        $this->settingServiceRegistry = $settingServiceRegistry;
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function handle() : void
    {

        $settingReadService = $this->settingServiceRegistry->getReadService();

        $settings = [
            'livestream' => [
                'endpoint' => $settingReadService->getValue(LivestreamSettingKey::ENDPOINT->value),
                'api_key' => $settingReadService->getValue(LivestreamSettingKey::API_KEY->value),
            ]
        ];

        $settings_json = json_encode($settings, JSON_THROW_ON_ERROR);
        $this->line($settings_json);
    }
}

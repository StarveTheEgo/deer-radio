<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\Setting\Service\SettingCreateService;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Setting\Service\SettingServiceRegistry;
use Illuminate\Database\Seeder;

abstract class AbstractSettingSeeder extends Seeder
{
    private SettingServiceRegistry $settingServiceRegistry;

    public function __construct(SettingServiceRegistry $settingServiceRegistry)
    {
        $this->settingServiceRegistry = $settingServiceRegistry;
    }

    protected function createNotExistingSettings(array $settings, int $initialOrdValue = 0) : void
    {
        $settingCreateService = $this->settingServiceRegistry->getCreateService();
        $settingReadService = $this->settingServiceRegistry->getReadService();

        foreach ($settings as $index => $setting) {
            if ($settingReadService->findByKey($setting->getKey())) {
                continue;
            }

            $setting->setOrd($initialOrdValue + $index * 10);
            $settingCreateService->create($setting);
        }
    }
}

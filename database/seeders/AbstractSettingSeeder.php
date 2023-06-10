<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\Setting\Service\SettingCreateService;
use App\Components\Setting\Service\SettingReadService;
use Illuminate\Database\Seeder;

abstract class AbstractSettingSeeder extends Seeder
{
    protected function getSettingReadService() : SettingReadService
    {
        return $this->container->get(SettingReadService::class);
    }

    protected function getSettingCreateService() : SettingCreateService
    {
        return $this->container->get(SettingCreateService::class);
    }

    protected function createNotExistingSettings(array $settings, int $initialOrdValue = 0) : void
    {
        $settingCreateService = $this->getSettingCreateService();
        $settingReadService = $this->getSettingReadService();

        foreach ($settings as $index => $setting) {
            if ($settingReadService->findByKey($setting->getKey())) {
                continue;
            }

            $setting->setOrd($initialOrdValue + $index * 10);
            $settingCreateService->create($setting);
        }
    }
}

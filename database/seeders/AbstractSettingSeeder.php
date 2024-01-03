<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\Setting\Service\SettingCreateService;
use App\Components\Setting\Service\SettingReadService;
use Illuminate\Database\Seeder;

abstract class AbstractSettingSeeder extends Seeder
{
    private SettingCreateService $settingCreateService;
    private SettingReadService $settingReadService;

    public function __construct(
        SettingCreateService $createService,
        SettingReadService $readService
    )
    {
        $this->settingCreateService = $createService;
        $this->settingReadService = $readService;
    }

    protected function createNotExistingSettings(array $settings, int $initialOrdValue = 0) : void
    {
        $settingCreateService = $this->settingCreateService;
        $settingReadService = $this->settingReadService;

        foreach ($settings as $index => $setting) {
            if ($settingReadService->findByKey($setting->getKey())) {
                continue;
            }

            $setting->setOrd($initialOrdValue + $index * 10);
            $settingCreateService->create($setting);
        }
    }
}

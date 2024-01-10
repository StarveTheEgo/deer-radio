<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient;

use App\Components\Setting\Service\SettingReadService;
use App\Components\UnsplashClient\Enum\UnsplashClientSettingKey;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UnsplashClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(UnsplashClient::class, function () {
            /** @var SettingReadService $settingReadService */
            $settingReadService = $this->app->get(SettingReadService::class);

            return new UnsplashClient(
                $settingReadService->getValue(UnsplashClientSettingKey::APP_ID->value),
                $settingReadService->getValue(UnsplashClientSettingKey::APP_NAME->value),
                $settingReadService->getValue(UnsplashClientSettingKey::APP_SECRET->value),
            );
        });
    }

    public function provides(): array
    {
        return [
            UnsplashClient::class,
        ];
    }
}

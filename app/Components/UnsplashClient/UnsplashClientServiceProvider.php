<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient;

use App\Components\Setting\Service\SettingReadService;
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
                $settingReadService->getValue('unsplash.app_id'),
                $settingReadService->getValue('unsplash.app_secret'),
                $settingReadService->getValue('unsplash.app_name')
            );
        });
    }

    public function provides()
    {
        return [
            UnsplashClient::class,
        ];
    }
}

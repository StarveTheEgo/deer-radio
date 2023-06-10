<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient;

use App\Components\Setting\Service\SettingReadService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UnsplashClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const SETTING_APP_ID = 'unsplash-client.app_id';
    public const SETTING_APP_NAME = 'unsplash-client.app_name';
    public const SETTING_APP_SECRET = 'unsplash-client.app_secret';

    public function register()
    {
        $this->app->singleton(UnsplashClient::class, function () {
            /** @var SettingReadService $settingReadService */
            $settingReadService = $this->app->get(SettingReadService::class);

            return new UnsplashClient(
                $settingReadService->getValue(self::SETTING_APP_ID),
                $settingReadService->getValue(self::SETTING_APP_NAME),
                $settingReadService->getValue(self::SETTING_APP_SECRET),
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

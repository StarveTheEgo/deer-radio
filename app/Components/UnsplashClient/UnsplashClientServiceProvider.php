<?php

declare(strict_types=1);

namespace App\Components\UnsplashClient;

use App\Components\ComponentData\Service\ComponentDataAccessService;
use App\Components\Setting\Service\SettingReadService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UnsplashClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        ComponentDataAccessService::class => ComponentDataAccessService::class,
    ];

    public function register()
    {
        $this->app->singleton(UnsplashClient::class, function (Application $app) {
            /** @var SettingReadService $settingReadService */
            $settingReadService = $app->get(SettingReadService::class);

            return new UnsplashClient(
                $settingReadService->getValue('unsplash.app_id'),
                $settingReadService->getValue('unsplash.app_secret'),
                $settingReadService->getValue('unsplash.app_name')
            );
        });
    }
}

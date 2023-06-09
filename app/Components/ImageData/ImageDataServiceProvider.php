<?php

declare(strict_types=1);

namespace App\Components\ImageData;

use App\Components\ImageData\Driver\Local\LocalDriver;
use App\Components\ImageData\Driver\Unsplash\UnsplashDriver;
use App\Components\Setting\Service\SettingReadService;
use Illuminate\Support\ServiceProvider;

class ImageDataServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ImageDataListProviderDriverRegistry::class, function () {
            $driverRegistry = new ImageDataListProviderDriverRegistry();
            foreach ($this->getImageProviderDriverClasses() as $driverClass) {
                $driverRegistry->registerDriver($this->app->get($driverClass));
            }
            return $driverRegistry;
        });

        $this->app->singleton(LocalDriver::class, function () {
            /** @var SettingReadService $settingReadService */
            $settingReadService = $this->app->get(SettingReadService::class);
            $imagePathsJson = $settingReadService->getValue('deer-radio.local_image_paths', '[]');

            return $this->app->makeWith(LocalDriver::class, [
                'imagePaths' => json_decode($imagePathsJson, true, flags: JSON_THROW_ON_ERROR),
            ]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            ImageDataListProviderDriverRegistry::class
        ];
    }

    private function getImageProviderDriverClasses(): array
    {
        return [
            LocalDriver::class,
            UnsplashDriver::class,
        ];
    }
}

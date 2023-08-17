<?php

declare(strict_types=1);

namespace App\Components\ImageData;

use App\Components\ImageData\Driver\LocalDriver;
use App\Components\ImageData\Driver\UnsplashDriver;
use App\Components\ImageData\Enum\LocalImageSettingKey;
use App\Components\ImageData\Enum\UnsplashDriverSettingKey;
use App\Components\Setting\Service\SettingReadService;
use Illuminate\Support\ServiceProvider;

class ImageDataServiceProvider extends ServiceProvider
{
    public const SETTING_IMAGE_PATHS = 'local-image.image_paths';

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

            $imagePathsJson = $settingReadService->getValue(LocalImageSettingKey::IMAGE_PATHS->value, '[]');
            $imagePaths = json_decode($imagePathsJson, true, flags: JSON_THROW_ON_ERROR);

            return new LocalDriver($imagePaths);
        });

        $this->app->singleton(UnsplashDriver::class, UnsplashDriver::class);
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
        $settingReadService = $this->app->get(SettingReadService::class);

        $driverClassList = [
            LocalDriver::class,
        ];

        /** @var SettingReadService $settingReadService */
        if ($settingReadService->getValue(UnsplashDriverSettingKey::IS_ENABLED->value)) {
            $driverClassList[] = UnsplashDriver::class;
        }

        return $driverClassList;
    }
}

<?php

declare(strict_types=1);

namespace App\Components\ImageData;

use App\Components\ImageData\Driver\LocalDriver;
use App\Components\ImageData\Driver\UnsplashDriver;
use App\Components\ImageData\Enum\LocalImageSettingKey;
use App\Components\ImageData\Enum\UnsplashDriverSettingKey;
use App\Components\Setting\Service\SettingReadService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImageDataServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array<class-string>
     */
    public array $singletons = [
        UnsplashImageDataFactory::class,
        ImageDataListProviderDriverRegistry::class,
        LocalDriver::class,
        UnsplashDriver::class,
    ];

    /**
     * @param ImageDataListProviderDriverRegistry $driverRegistry
     * @param LocalDriver $localDriver
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws JsonException
     */
    public function boot(
        ImageDataListProviderDriverRegistry $driverRegistry,
        LocalDriver $localDriver
    ) : void
    {
        foreach ($this->getImageProviderDriverClasses() as $driverClass) {
            $driverRegistry->registerDriver($this->app->get($driverClass));
        }

        /** @var SettingReadService $settingReadService */
        $settingReadService = $this->app->get(SettingReadService::class);

        $imagePathsJson = $settingReadService->getValue(LocalImageSettingKey::IMAGE_PATHS->value, '[]');
        $imagePaths = json_decode($imagePathsJson, true, flags: JSON_THROW_ON_ERROR);
        $localDriver->setImagePaths($imagePaths);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            ImageDataListProviderDriverRegistry::class,
            LocalDriver::class,
            UnsplashDriver::class,
            UnsplashImageDataFactory::class,
        ];
    }

    /**
     * @return string[]
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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

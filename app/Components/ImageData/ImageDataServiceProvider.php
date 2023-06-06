<?php

declare(strict_types=1);

namespace App\Components\ImageData;

use App\Components\ImageData\Driver\StaticList\StaticListDriver;
use App\Components\ImageData\Driver\Unsplash\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\ImageData\Driver\Unsplash\UnsplashDriver;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
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

        $this->app
            ->when(UnsplashDriver::class)
            ->needs(UnsplashSearchQueryBuilderInterface::class)
            ->give(DeerRadioUnsplashSearchQueryBuilder::class);
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
            StaticListDriver::class,
            UnsplashDriver::class,
        ];
    }
}

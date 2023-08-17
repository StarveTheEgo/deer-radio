<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\DeerRadio\Commands\DeerImageUpdate;
use App\Components\DeerRadio\UnsplashSearchQuery\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\ImageData\Driver\UnsplashDriver;
use App\Components\ImageData\ImageDataListProviderDriverRegistry;
use App\Components\Photoban\Service\PhotobanReadService;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Psr\Log\LoggerInterface;

class DeerRadioServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const COMPONENT_NAME = 'DeerRadio';

    public function register()
    {
        $this->app->singleton(DeerImageManager::class, function () {
            $app = $this->app;
            /** @var FilesystemManager $filesystemManager */
            $filesystemManager = $this->app->get(FilesystemManager::class);

            return new DeerImageManager(
                $app->get(ImageDataListProviderDriverRegistry::class),
                $filesystemManager->disk('public'),
                $filesystemManager->disk('temp'),
                $app->get(ImageManager::class),
                $app->get(PhotobanReadService::class),
                $app->get(DeerRadioDataAccessor::class),
                $app->get(LoggerInterface::class)
            );
        });

        $this->app
            ->when(UnsplashDriver::class)
            ->needs(UnsplashSearchQueryBuilderInterface::class)
            ->give(DeerRadioUnsplashSearchQueryBuilder::class);
    }

    public function boot() : void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeerImageUpdate::class,
            ]);
        }
    }

    /**
     * @inheritDoc
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            DeerImageManager::class
        ];
    }
}

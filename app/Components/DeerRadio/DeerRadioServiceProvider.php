<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\ComponentData\Service\ComponentDataAccessService;
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
            /** @var ComponentDataAccessService $componentDataAccessService */
            $componentDataAccessService = $this->app->get(ComponentDataAccessService::class);

            return new DeerImageManager(
                $app->get(ImageDataListProviderDriverRegistry::class),
                $filesystemManager->disk('public'),
                $filesystemManager->disk('temp'),
                $app->get(ImageManager::class),
                $app->get(PhotobanReadService::class),
                $componentDataAccessService->buildAccessor(self::COMPONENT_NAME),
                $app->get(LoggerInterface::class)
            );
        });

        $this->app
            ->when(UnsplashDriver::class)
            ->needs(UnsplashSearchQueryBuilderInterface::class)
            ->give(DeerRadioUnsplashSearchQueryBuilder::class);
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

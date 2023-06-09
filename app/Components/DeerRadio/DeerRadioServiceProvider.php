<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\ComponentData\Service\ComponentDataAccessService;
use App\Components\ImageData\Driver\Unsplash\UnsplashDriver;
use App\Components\ImageData\ImageDataListProviderDriverRegistry;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class DeerRadioServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const COMPONENT_NAME = 'DeerRadio';

    public $singletons = [
        DeerImageManager::class,
    ];

    public function register()
    {
        $this->app->singleton(ImageDataListProviderDriverRegistry::class, function () {
            /** @var FilesystemManager $filesystemManager */
            $filesystemManager = $this->app->get(FilesystemManager::class);
            /** @var ComponentDataAccessService $componentDataAccessService */
            $componentDataAccessService = $this->app->get(ComponentDataAccessService::class);

            return $this->app->makeWith(DeerImageManager::class, [
                'deerImageStorage' => $filesystemManager->disk('public'),
                'tempStorage' => $filesystemManager->disk('temp'),
                'componentDataAccessor' => $componentDataAccessService->buildAccessor(self::COMPONENT_NAME),
            ]);
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

<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\DeerRadio\Commands\DeerImageUpdate;
use App\Components\DeerRadio\Commands\GetCurrentDeerImage;
use App\Components\DeerRadio\Commands\GetNextSong;
use App\Components\DeerRadio\Commands\GetRadioSettings;
use App\Components\DeerRadio\Commands\UpdateNowPlayingId;
use App\Components\DeerRadio\Service\CurrentSongUpdateService;
use App\Components\DeerRadio\Service\DeerImageDeleteService;
use App\Components\DeerRadio\Service\DeerImageUpdateService;
use App\Components\DeerRadio\Service\SongAnnotateService;
use App\Components\DeerRadio\Service\SongPickService;
use App\Components\DeerRadio\Service\SongQueueService;
use App\Components\DeerRadio\UnsplashSearchQuery\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\Storage\Enum\StorageName;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class DeerRadioServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const COMPONENT_NAME = 'DeerRadio';

    /**
     * @var array<class-string, class-string>
     */
    public array $singletons = [
        UnsplashSearchQueryBuilderInterface::class => DeerRadioUnsplashSearchQueryBuilder::class,
    ];

    public function register()
    {
        $this->registerDeerImageUpdateService();
        $this->registerDeerImageDeleteService();
    }

    public function boot() : void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeerImageUpdate::class,
                GetCurrentDeerImage::class,
                GetNextSong::class,
                GetRadioSettings::class,
                UpdateNowPlayingId::class,
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
            CurrentSongUpdateService::class,
            DeerImageDeleteService::class,
            DeerImageUpdateService::class,
            SongAnnotateService::class,
            SongPickService::class,
            SongQueueService::class,
        ];
    }

    private function registerDeerImageUpdateService() : void
    {
        $app = $this->app;

        /** @var FilesystemManager $filesystemManager */
        $filesystemManager = $app->get(FilesystemManager::class);

        $app->singleton(DeerImageUpdateService::class);

        $app
            ->when(DeerImageUpdateService::class)
            ->needs('$deerImageStorage')
            ->give(function () use ($filesystemManager) {
                return $filesystemManager->disk(StorageName::PUBLIC_STORAGE->value);
            });

        $app
            ->when(DeerImageUpdateService::class)
            ->needs('$tempStorage')
            ->give(function () use ($filesystemManager) {
                return $filesystemManager->disk(StorageName::TEMP_STORAGE->value);
            });
    }

    private function registerDeerImageDeleteService() : void
    {
        $app = $this->app;

        /** @var FilesystemManager $filesystemManager */
        $filesystemManager = $app->get(FilesystemManager::class);

        $app->singleton(DeerImageDeleteService::class);

        $app
            ->when(DeerImageDeleteService::class)
            ->needs('$deerImageStorage')
            ->give(function () use ($filesystemManager) {
                return $filesystemManager->disk(StorageName::PUBLIC_STORAGE->value);
            });
    }
}
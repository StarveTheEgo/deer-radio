<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\DeerRadio\Http\Controllers\Api\Chat\DeerLivestreamChatController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerImage\DeerImageIndexController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerImage\DeerImageUpdateController;
use App\Components\DeerRadio\Http\Controllers\Api\Liquidsoap\DeerMusic\DeerMusicQueueController;
use App\Components\DeerRadio\Http\Controllers\Api\Settings\DeerRadioSettingsController;
use App\Components\DeerRadio\Service\CurrentSongUpdateService;
use App\Components\DeerRadio\Service\DeerImageDeleteService;
use App\Components\DeerRadio\Service\DeerImageUpdateService;
use App\Components\DeerRadio\Service\SongPickService;
use App\Components\DeerRadio\Service\SongQueueService;
use App\Components\DeerRadio\UnsplashSearchQuery\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\Storage\Enum\StorageName;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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

    public function boot(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar
            ->prefix('internal')
            ->group(function() use ($routeRegistrar) {
                $routeRegistrar->get('settings', [DeerRadioSettingsController::class, 'index']);

                $routeRegistrar->get('deer-image/current', [DeerImageIndexController::class, 'index']);
                $routeRegistrar->get('deer-image/current', [DeerImageUpdateController::class, 'update']);

                $routeRegistrar->get('song-queue/enqueue/auto', [DeerMusicQueueController::class, 'enqueueNextSong']);
                $routeRegistrar->get('song-queue/update-current-song', [DeerMusicQueueController::class, 'updateCurrentSongId']);

                $routeRegistrar->get('/stream-chat/send-message', [DeerLivestreamChatController::class, 'sendMessage']);
            });
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
            SongPickService::class,
            SongQueueService::class,

            DeerLivestreamChatController::class,
            DeerImageIndexController::class,
            DeerImageUpdateController::class,
            DeerMusicQueueController::class,
            DeerRadioSettingsController::class,
        ];
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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

<?php

declare(strict_types=1);

namespace App\Components\DeerRadio;

use App\Components\DeerRadio\Http\Controllers\Api\Chat\DeerLivestreamChatController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerImage\DeerImageIndexController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerImage\DeerImageUpdateController;
use App\Components\DeerRadio\Http\Controllers\Api\DeerMusic\DeerMusicQueueController;
use App\Components\DeerRadio\Http\Controllers\Api\Settings\DeerRadioSettingsController;
use App\Components\DeerRadio\Service\CurrentSongUpdateService;
use App\Components\DeerRadio\Service\DeerImageDeleteService;
use App\Components\DeerRadio\Service\DeerImageUpdateService;
use App\Components\DeerRadio\Service\SongPickService;
use App\Components\DeerRadio\Service\SongQueueService;
use App\Components\DeerRadio\UnsplashSearchQuery\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\ImageData\ImageDataListProviderDriverRegistry;
use App\Components\Photoban\Service\PhotobanReadService;
use App\Components\Storage\Enum\StorageName;
use App\Components\UnsplashClient\UnsplashQuery\UnsplashSearchQueryBuilderInterface;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class DeerRadioServiceProvider extends ServiceProvider
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

        $app->singleton(DeerImageUpdateService::class, function () use ($app, $filesystemManager) {
            $radioStorage = $filesystemManager->disk(StorageName::RADIO_STORAGE->value);
            $tempStorage = $filesystemManager->disk(StorageName::TEMP_STORAGE->value);

            return new DeerImageUpdateService(
                $app->get(ImageDataListProviderDriverRegistry::class),
                $radioStorage,
                $tempStorage,
                $app->get(ImageManager::class),
                $app->get(PhotobanReadService::class),
                $app->get(DeerRadioDataAccessor::class),
                $app->get(LoggerInterface::class),
            );
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

        $app->singleton(DeerImageDeleteService::class, function () use ($app, $filesystemManager) {
            $radioStorage = $filesystemManager->disk(StorageName::RADIO_STORAGE->value);

            return new DeerImageDeleteService($radioStorage,);
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Google;

use App\Components\Google\Factory\GoogleOutputConfigFactory;
use App\Components\Google\Factory\YoutubeApiFactory;
use App\Components\Google\Filler\YouTubeLiveBroadcastFiller;
use App\Components\Google\Filler\YouTubeLiveStreamFiller;
use App\Components\Google\Output\GoogleOutputDriver;
use App\Components\Google\Service\BindLiveBroadcastService;
use App\Components\Google\Service\CreateOrUpdateLiveBroadcastService;
use App\Components\Google\Service\CreateOrUpdateLiveStreamService;
use App\Components\Output\Registry\OutputDriverRegistry;
use App\Components\Google\Factory\GoogleClientFactory;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GoogleServiceProvider extends ServiceProvider
{
    public const COMPONENT_NAME = 'google';

    /**
     * @var array<class-string>
     */
    public array $singletons = [
        GoogleOutputConfigFactory::class,
        GoogleClientFactory::class,
        YoutubeApiFactory::class,

        YouTubeLiveBroadcastFiller::class,
        YouTubeLiveStreamFiller::class,

        GoogleOutputDriver::class,

        BindLiveBroadcastService::class,
        CreateOrUpdateLiveBroadcastService::class,
        CreateOrUpdateLiveStreamService::class,

        GoogleDataAccessor::class,
    ];

    public function provides(): array
    {
        return [
            GoogleOutputConfigFactory::class,
            GoogleClientFactory::class,
            YoutubeApiFactory::class,

            YouTubeLiveBroadcastFiller::class,
            YouTubeLiveStreamFiller::class,

            GoogleOutputDriver::class,

            BindLiveBroadcastService::class,
            CreateOrUpdateLiveBroadcastService::class,
            CreateOrUpdateLiveStreamService::class,

            GoogleDataAccessor::class,
        ];
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function boot() : void
    {
        /** @var OutputDriverRegistry $driverRegistry */
        $driverRegistry = $this->app->get(OutputDriverRegistry::class);

        $driverRegistry->registerDriverClass(GoogleOutputDriver::class);
    }
}

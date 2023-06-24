<?php

declare(strict_types=1);

namespace App\Components\Song;

use App\Components\Song\Entity\Song;
use App\Components\Song\Repository\SongRepository;
use App\Components\Song\Repository\SongRepositoryInterface;
use App\Components\Song\Service\SongCreateService;
use App\Components\Song\Service\SongDeleteService;
use App\Components\Song\Service\SongReadService;
use App\Components\Song\Service\SongUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SongServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        SongCreateService::class => SongCreateService::class,
        SongReadService::class => SongReadService::class,
        SongUpdateService::class => SongUpdateService::class,
        SongDeleteService::class => SongDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SongRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Song::class));

            return new SongRepository(
                $em,
                $entityRepository
            );
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
            SongCreateService::class,
            SongReadService::class,
            SongUpdateService::class,
            SongDeleteService::class,
            SongRepositoryInterface::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Album;

use App\Components\Album\Entity\Album;
use App\Components\Album\Repository\AlbumRepository;
use App\Components\Album\Repository\AlbumRepositoryInterface;
use App\Components\Album\Service\AlbumCreateService;
use App\Components\Album\Service\AlbumDeleteService;
use App\Components\Album\Service\AlbumReadService;
use App\Components\Album\Service\AlbumUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AlbumServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        AlbumCreateService::class => AlbumCreateService::class,
        AlbumReadService::class => AlbumReadService::class,
        AlbumUpdateService::class => AlbumUpdateService::class,
        AlbumDeleteService::class => AlbumDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AlbumRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Album::class));

            return new AlbumRepository(
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
            AlbumCreateService::class,
            AlbumReadService::class,
            AlbumUpdateService::class,
            AlbumDeleteService::class,
            AlbumRepositoryInterface::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Photoban;

use App\Components\Photoban\Entity\Photoban;
use App\Components\Photoban\Repository\PhotobanRepository;
use App\Components\Photoban\Repository\PhotobanRepositoryInterface;
use App\Components\Photoban\Service\PhotobanCreateService;
use App\Components\Photoban\Service\PhotobanDeleteService;
use App\Components\Photoban\Service\PhotobanReadService;
use App\Components\Photoban\Service\PhotobanUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PhotobanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        PhotobanCreateService::class => PhotobanCreateService::class,
        PhotobanReadService::class => PhotobanReadService::class,
        PhotobanUpdateService::class => PhotobanUpdateService::class,
        PhotobanDeleteService::class => PhotobanDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PhotobanRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Photoban::class));

            return new PhotobanRepository(
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
            PhotobanCreateService::class,
            PhotobanReadService::class,
            PhotobanUpdateService::class,
            PhotobanDeleteService::class,
            PhotobanRepositoryInterface::class,
        ];
    }
}

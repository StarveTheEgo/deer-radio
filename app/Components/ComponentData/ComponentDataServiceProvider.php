<?php

namespace App\Components\ComponentData;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Repository\ComponentDataRepository;
use App\Components\ComponentData\Repository\ComponentDataRepositoryInterface;
use App\Components\ComponentData\Service\ComponentDataAccessService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ComponentDataServiceProvider extends ServiceProvider
{
    public const SERVICE_NS = 'component-data';

    public $singletons = [
        ComponentDataAccessService::class => ComponentDataAccessService::class,
    ];

    public function register()
    {
        $this->app->singleton(ComponentDataRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(ComponentData::class));

            return new ComponentDataRepository(
                $em,
                $entityRepository
            );
        });
    }

     public function boot()
     {
     }
}

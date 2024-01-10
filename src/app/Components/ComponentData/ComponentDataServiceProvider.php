<?php

declare(strict_types=1);

namespace App\Components\ComponentData;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Repository\ComponentDataRepository;
use App\Components\ComponentData\Repository\ComponentDataRepositoryInterface;
use App\Components\ComponentData\Service\ComponentDataAccessService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ComponentDataServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * @var array<class-string>
     */
    public $singletons = [
        ComponentDataAccessService::class,
    ];

    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ComponentDataRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(ComponentData::class));

            return new ComponentDataRepository($em, $entityRepository);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            ComponentDataAccessService::class,
            ComponentDataRepositoryInterface::class,
        ];
    }
}

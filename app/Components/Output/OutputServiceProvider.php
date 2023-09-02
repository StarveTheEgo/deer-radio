<?php

declare(strict_types=1);

namespace App\Components\Output;

use App\Components\Output\Driver\DummyOutputDriver;
use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepository;
use App\Components\Output\Repository\OutputRepositoryInterface;
use App\Components\Output\Service\OutputCreateService;
use App\Components\Output\Service\OutputDeleteService;
use App\Components\Output\Service\OutputReadService;
use App\Components\Output\Service\OutputServiceRegistry;
use App\Components\Output\Service\OutputUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OutputServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        OutputCreateService::class,
        OutputReadService::class,
        OutputUpdateService::class,
        OutputDeleteService::class,
        OutputServiceRegistry::class,
        OutputDriverRegistry::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(OutputRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Output::class));

            return new OutputRepository(
                $em,
                $entityRepository
            );
        });
    }

    public function boot() : void
    {
        /** @var OutputDriverRegistry $driverRegistry */
        $driverRegistry = $this->app->get(OutputDriverRegistry::class);
        $driverRegistry->registerDriverClass(DummyOutputDriver::class);
    }

    /**
     * @inheritDoc
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            OutputCreateService::class,
            OutputReadService::class,
            OutputUpdateService::class,
            OutputDeleteService::class,
            OutputServiceRegistry::class,
            OutputRepositoryInterface::class,
            OutputDriverRegistry::class,
            DummyOutputDriver::class,
        ];
    }
}

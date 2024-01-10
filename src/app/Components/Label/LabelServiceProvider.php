<?php

declare(strict_types=1);

namespace App\Components\Label;

use App\Components\Label\Entity\Label;
use App\Components\Label\Repository\LabelRepository;
use App\Components\Label\Repository\LabelRepositoryInterface;
use App\Components\Label\Service\LabelCreateService;
use App\Components\Label\Service\LabelDeleteService;
use App\Components\Label\Service\LabelReadService;
use App\Components\Label\Service\LabelUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class LabelServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        LabelCreateService::class => LabelCreateService::class,
        LabelReadService::class => LabelReadService::class,
        LabelUpdateService::class => LabelUpdateService::class,
        LabelDeleteService::class => LabelDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(LabelRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Label::class));

            return new LabelRepository(
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
            LabelCreateService::class,
            LabelReadService::class,
            LabelUpdateService::class,
            LabelDeleteService::class,
            LabelRepositoryInterface::class,
        ];
    }
}

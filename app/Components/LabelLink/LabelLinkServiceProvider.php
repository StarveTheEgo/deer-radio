<?php

declare(strict_types=1);

namespace App\Components\LabelLink;

use App\Components\LabelLink\Entity\LabelLink;
use App\Components\LabelLink\Repository\LabelLinkRepository;
use App\Components\LabelLink\Repository\LabelLinkRepositoryInterface;
use App\Components\LabelLink\Service\LabelLinkCreateService;
use App\Components\LabelLink\Service\LabelLinkDeleteService;
use App\Components\LabelLink\Service\LabelLinkReadService;
use App\Components\LabelLink\Service\LabelLinkUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class LabelLinkServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        LabelLinkCreateService::class => LabelLinkCreateService::class,
        LabelLinkReadService::class => LabelLinkReadService::class,
        LabelLinkUpdateService::class => LabelLinkUpdateService::class,
        LabelLinkDeleteService::class => LabelLinkDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(LabelLinkRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(LabelLink::class));

            return new LabelLinkRepository(
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
            LabelLinkCreateService::class,
            LabelLinkReadService::class,
            LabelLinkUpdateService::class,
            LabelLinkDeleteService::class,
            LabelLinkRepositoryInterface::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\AuthorLink;

use App\Components\AuthorLink\Entity\AuthorLink;
use App\Components\AuthorLink\Repository\AuthorLinkRepository;
use App\Components\AuthorLink\Repository\AuthorLinkRepositoryInterface;
use App\Components\AuthorLink\Service\AuthorLinkCreateService;
use App\Components\AuthorLink\Service\AuthorLinkDeleteService;
use App\Components\AuthorLink\Service\AuthorLinkReadService;
use App\Components\AuthorLink\Service\AuthorLinkUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AuthorLinkServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        AuthorLinkCreateService::class => AuthorLinkCreateService::class,
        AuthorLinkReadService::class => AuthorLinkReadService::class,
        AuthorLinkUpdateService::class => AuthorLinkUpdateService::class,
        AuthorLinkDeleteService::class => AuthorLinkDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AuthorLinkRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(AuthorLink::class));

            return new AuthorLinkRepository(
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
            AuthorLinkCreateService::class,
            AuthorLinkReadService::class,
            AuthorLinkUpdateService::class,
            AuthorLinkDeleteService::class,
            AuthorLinkRepositoryInterface::class,
        ];
    }
}

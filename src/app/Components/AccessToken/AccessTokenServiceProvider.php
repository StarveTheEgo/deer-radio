<?php

declare(strict_types=1);

namespace App\Components\AccessToken;

use App\Components\AccessToken\Entity\AccessToken;
use App\Components\AccessToken\Repository\AccessTokenRepository;
use App\Components\AccessToken\Repository\AccessTokenRepositoryInterface;
use App\Components\AccessToken\Service\AccessTokenCreateService;
use App\Components\AccessToken\Service\AccessTokenDeleteService;
use App\Components\AccessToken\Service\AccessTokenReadService;
use App\Components\AccessToken\Service\AccessTokenUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AccessTokenServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $singletons = [
        AccessTokenCreateService::class,
        AccessTokenReadService::class,
        AccessTokenUpdateService::class,
        AccessTokenDeleteService::class,
    ];

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $this->app->singleton(AccessTokenRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(AccessToken::class));

            return new AccessTokenRepository(
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
            AccessTokenCreateService::class,
            AccessTokenReadService::class,
            AccessTokenUpdateService::class,
            AccessTokenDeleteService::class,
            AccessTokenRepositoryInterface::class,
        ];
    }
}

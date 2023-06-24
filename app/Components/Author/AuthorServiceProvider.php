<?php

declare(strict_types=1);

namespace App\Components\Author;

use App\Components\Author\Entity\Author;
use App\Components\Author\Repository\AuthorRepository;
use App\Components\Author\Repository\AuthorRepositoryInterface;
use App\Components\Author\Service\AuthorCreateService;
use App\Components\Author\Service\AuthorDeleteService;
use App\Components\Author\Service\AuthorReadService;
use App\Components\Author\Service\AuthorUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AuthorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        AuthorCreateService::class => AuthorCreateService::class,
        AuthorReadService::class => AuthorReadService::class,
        AuthorUpdateService::class => AuthorUpdateService::class,
        AuthorDeleteService::class => AuthorDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AuthorRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Author::class));

            return new AuthorRepository(
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
            AuthorCreateService::class,
            AuthorReadService::class,
            AuthorUpdateService::class,
            AuthorDeleteService::class,
            AuthorRepositoryInterface::class,
        ];
    }
}

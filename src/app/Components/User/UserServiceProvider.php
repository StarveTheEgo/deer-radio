<?php

declare(strict_types=1);

namespace App\Components\User;

use App\Components\User\Entity\User;
use App\Components\User\Repository\UserRepository;
use App\Components\User\Repository\UserRepositoryInterface;
use App\Components\User\Service\UserCreateService;
use App\Components\User\Service\UserDeleteService;
use App\Components\User\Service\UserReadService;
use App\Components\User\Service\UserUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array<class-string>
     */
    public array $singletons = [
        UserCreateService::class,
        UserReadService::class,
        UserUpdateService::class,
        UserDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(User::class));

            return new UserRepository(
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
            UserCreateService::class,
            UserReadService::class,
            UserUpdateService::class,
            UserDeleteService::class,
            UserRepositoryInterface::class,
        ];
    }
}

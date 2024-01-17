<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount;

use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Http\Controllers\ServiceAccountController;
use App\Components\ServiceAccount\Repository\ServiceAccountRepository;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;
use App\Components\ServiceAccount\Service\ServiceAccountCreateService;
use App\Components\ServiceAccount\Service\ServiceAccountDeleteService;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use App\Components\ServiceAccount\Service\ServiceAccountUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ServiceAccountServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $singletons = [
        ServiceAccountCreateService::class,
        ServiceAccountReadService::class,
        ServiceAccountUpdateService::class,
        ServiceAccountDeleteService::class,
    ];

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $this->app->singleton(ServiceAccountRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(ServiceAccount::class));

            return new ServiceAccountRepository(
                $em,
                $entityRepository
            );
        });
    }

    public function boot(RouteRegistrar $routeRegistrar) : void
    {
        Route::prefix('service-account')
            ->middleware('web')
            ->group(function () use ($routeRegistrar) {
                $routeRegistrar->get('oauth-redirect/{serviceAccount}', [ServiceAccountController::class, 'redirect']);

                $routeRegistrar->get('oauth-callback/{serviceName}', [ServiceAccountController::class, 'callback']);
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
            ServiceAccountCreateService::class,
            ServiceAccountReadService::class,
            ServiceAccountUpdateService::class,
            ServiceAccountDeleteService::class,
            ServiceAccountRepositoryInterface::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount;

use App\Components\OrchidIntergration\Enum\PlatformConfigKey;
use App\Components\OrchidIntergration\Enum\PlatformRoute;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Enum\ServiceAccountRoute;
use App\Components\ServiceAccount\Http\Controllers\ServiceAccountController;
use App\Components\ServiceAccount\Orchid\Screen\ServiceAccountEditScreen;
use App\Components\ServiceAccount\Orchid\Screen\ServiceAccountIndexScreen;
use App\Components\ServiceAccount\Repository\ServiceAccountRepository;
use App\Components\ServiceAccount\Repository\ServiceAccountRepositoryInterface;
use App\Components\ServiceAccount\Service\ServiceAccountCreateService;
use App\Components\ServiceAccount\Service\ServiceAccountDeleteService;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use App\Components\ServiceAccount\Service\ServiceAccountUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tabuna\Breadcrumbs\Trail;

class ServiceAccountServiceProvider extends ServiceProvider
{
    public const RESOURCE_NS = 'service-account';

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

    public function boot(ConfigRepository $config) : void
    {
        $middlewareName = $config->get(PlatformConfigKey::PRIVATE_MIDDLEWARE->value);

        Route::middleware($middlewareName)
            ->group(function () {
                // oauth routes group
                Route::prefix('admin/service-accounts')
                    ->group(function () {
                        Route::get('oauth-redirect/{serviceAccount}', [ServiceAccountController::class, 'redirect'])
                            ->name(ServiceAccountRoute::OAUTH_REDIRECT->value);

                        Route::get('oauth-callback/{serviceNameValue}', [ServiceAccountController::class, 'callback'])
                            ->name(ServiceAccountRoute::OAUTH_CALLBACK->value);

                        Route::get('oauth-disconnect/{serviceAccount}', [ServiceAccountController::class, 'disconnect'])
                            ->name(ServiceAccountRoute::OAUTH_DISCONNECT->value);
                    });

                // index route
                Route::screen('admin/service-accounts', ServiceAccountIndexScreen::class)
                    ->name(ServiceAccountRoute::INDEX->value)
                    ->breadcrumbs(function (Trail $trail) {
                        return $trail
                            ->parent(PlatformRoute::INDEX->value)
                            ->push(__('Service Accounts'), route(ServiceAccountRoute::INDEX->value));
                    });

                // edit route
                Route::screen('service-accounts/{account}/edit', ServiceAccountEditScreen::class)
                    ->name(ServiceAccountRoute::EDIT->value)
                    ->breadcrumbs(function (Trail $trail, ?ServiceAccount $serviceAccount) {
                        return $trail
                            ->parent(ServiceAccountRoute::INDEX->value)
                            ->push(__('Edit account'), route(ServiceAccountRoute::EDIT->value, $serviceAccount?->getId()));
                    });

                // create route
                Route::screen('admin/service-accounts/create', ServiceAccountEditScreen::class)
                    ->name(ServiceAccountRoute::CREATE->value)
                    ->breadcrumbs(function (Trail $trail) {
                        return $trail
                            ->parent(ServiceAccountRoute::INDEX->value)
                            ->push(__('Create account'), route(ServiceAccountRoute::CREATE->value));
                    });
            });

        $this->loadViewsFrom(__DIR__.'/resources/views', self::RESOURCE_NS);
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

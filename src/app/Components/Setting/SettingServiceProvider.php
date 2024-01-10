<?php

declare(strict_types=1);

namespace App\Components\Setting;

use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Repository\SettingRepository;
use App\Components\Setting\Repository\SettingRepositoryInterface;
use App\Components\Setting\Service\SettingCreateService;
use App\Components\Setting\Service\SettingDeleteService;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Setting\Service\SettingUpdateService;
use App\Components\Setting\Service\SettingValueService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const SERVICE_NS = 'setting';

    /**
     * @var array<class-string>
     */
    public $singletons = [
        SettingCreateService::class,
        SettingReadService::class,
        SettingUpdateService::class,
        SettingDeleteService::class,
        SettingValueService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SettingRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Setting::class));

            return new SettingRepository(
                $em,
                $entityRepository
            );
        });

        $this->loadViewsFrom(__DIR__.'/resources/views', self::SERVICE_NS);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            SettingCreateService::class,
            SettingReadService::class,
            SettingUpdateService::class,
            SettingDeleteService::class,
            SettingValueService::class,

            SettingRepositoryInterface::class,
        ];
    }
}

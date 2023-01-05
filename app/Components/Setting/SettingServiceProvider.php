<?php

namespace App\Components\Setting;

use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\Factory\Input\InputFieldFactory;
use App\Components\Setting\Orchid\Field\FieldFactoryRegistry;
use App\Components\Setting\Orchid\Screen\SettingScreen;
use App\Components\Setting\Repository\SettingRepository;
use App\Components\Setting\Repository\SettingRepositoryInterface;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Setting\Service\SettingUpdateService;
use App\Models\Song;
use App\Observers\SongObserver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public const SERVICE_NS = 'radio-setting';

    public $singletons = [
        FieldFactoryRegistry::class => FieldFactoryRegistry::class,
        SettingReadService::class => SettingReadService::class,
        SettingUpdateService::class => SettingUpdateService::class,
        SettingScreen::class => SettingScreen::class,
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Song::observe(SongObserver::class);

        /** @var FieldFactoryRegistry $fieldFactoryRegistry */
        $fieldFactoryRegistry = $this->app->get(FieldFactoryRegistry::class);
        foreach ($this->getFieldFactoryClasses() as $factoryClass) {
            $fieldFactoryRegistry->registerFactory($this->app->get($factoryClass));
        }

        $this->loadViewsFrom(__DIR__.'/resources/views', self::SERVICE_NS);
    }

    private function getFieldFactoryClasses(): array
    {
        return [
            InputFieldFactory::class,
        ];
    }
}

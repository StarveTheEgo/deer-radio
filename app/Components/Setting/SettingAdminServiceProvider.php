<?php

declare(strict_types=1);

namespace App\Components\Setting;

use App\Components\Setting\Orchid\Field\Factory\Input\InputFieldFactory;
use App\Components\Setting\Orchid\Field\Factory\Toggle\ToggleFieldFactory;
use App\Components\Setting\Orchid\Field\FieldFactoryRegistry;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SettingAdminServiceProvider extends ServiceProvider
{
    public const SERVICE_NS = 'radio-admin-setting';

    public $singletons = [
        FieldFactoryRegistry::class => FieldFactoryRegistry::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function boot()
    {
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
            ToggleFieldFactory::class,
        ];
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            FieldFactoryRegistry::class => FieldFactoryRegistry::class,
        ];
    }
}

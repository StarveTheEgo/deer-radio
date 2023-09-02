<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration;

use App\Components\OrchidIntergration\Field\Factory\Input\InputFieldFactory;
use App\Components\OrchidIntergration\Field\Factory\Toggle\ToggleFieldFactory;
use App\Components\OrchidIntergration\Field\FieldFactoryRegistry;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OrchidIntegrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        FieldFactoryRegistry::class => FieldFactoryRegistry::class,
        InputFieldFactory::class => InputFieldFactory::class,
        ToggleFieldFactory::class => ToggleFieldFactory::class,
    ];

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

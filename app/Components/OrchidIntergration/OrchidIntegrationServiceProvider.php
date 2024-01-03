<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Code\Factory\CodeFieldFactory;
use App\Components\OrchidIntergration\Field\Code\Factory\CodeFieldOptionsFactory;
use App\Components\OrchidIntergration\Field\Input\Factory\InputFieldFactory;
use App\Components\OrchidIntergration\Field\Input\Factory\InputFieldOptionsFactory;
use App\Components\OrchidIntergration\Field\Toggle\Factory\ToggleFieldFactory;
use App\Components\OrchidIntergration\Field\Toggle\Factory\ToggleFieldOptionsFactory;
use App\Components\OrchidIntergration\Registry\FieldFactoryRegistry;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OrchidIntegrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array<class-string>
     */
    public $singletons = [
        FieldFactoryRegistry::class,

        InputFieldFactory::class,
        InputFieldOptionsFactory::class,

        ToggleFieldFactory::class,
        ToggleFieldOptionsFactory::class,

        CodeFieldFactory::class,
        CodeFieldOptionsFactory::class,
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
        // registering field factories
        foreach ($this->getFieldFactoryClasses() as $fieldTypeValue => $fieldFactoryClass) {
            $fieldType = FieldType::tryFrom($fieldTypeValue);
            $fieldFactoryRegistry->registerFieldFactory($fieldType, $this->app->get($fieldFactoryClass));
        }

        // registering field options factories
        foreach ($this->getFieldOptionsFactoryClasses() as $fieldTypeValue => $fieldOptionsFactoryClass) {
            $fieldType = FieldType::tryFrom($fieldTypeValue);
            $fieldFactoryRegistry->registerFieldOptionsFactory($fieldType, $this->app->get($fieldOptionsFactoryClass));
        }
    }

    /**
     * @return array<string, class-string>
     */
    private function getFieldFactoryClasses(): array
    {
        return [
            FieldType::INPUT->value => InputFieldFactory::class,
            FieldType::TOGGLE->value => ToggleFieldFactory::class,
            FieldType::CODE->value => CodeFieldFactory::class,
        ];
    }

    /**
     * @return array<string, class-string>
     */
    private function getFieldOptionsFactoryClasses(): array
    {
        return [
            FieldType::INPUT->value => InputFieldOptionsFactory::class,
            FieldType::TOGGLE->value => ToggleFieldOptionsFactory::class,
            FieldType::CODE->value => CodeFieldOptionsFactory::class,
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
            FieldFactoryRegistry::class,

            InputFieldFactory::class,
            InputFieldOptionsFactory::class,

            ToggleFieldFactory::class,
            ToggleFieldOptionsFactory::class,

            CodeFieldFactory::class,
            CodeFieldOptionsFactory::class,
        ];
    }
}

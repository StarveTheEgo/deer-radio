<?php

declare(strict_types=1);

namespace App\Components\Icecast;

use App\Components\Icecast\Factory\IcecastOutputConfigFactory;
use App\Components\Icecast\Output\IcecastOutputDriver;
use App\Components\Output\Registry\OutputDriverRegistry;
use Illuminate\Support\ServiceProvider;

class IcecastServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string>
     */
    public array $singletons = [
        IcecastOutputConfigFactory::class,
        IcecastOutputDriver::class,
    ];

    #[\Override]
    public function provides(): array
    {
        return [
            IcecastOutputConfigFactory::class,
            IcecastOutputDriver::class,
        ];
    }

    public function boot() : void
    {
        /** @var OutputDriverRegistry $driverRegistry */
        $driverRegistry = $this->app->get(OutputDriverRegistry::class);

        $driverRegistry->registerDriverClass(IcecastOutputDriver::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Output\Factory;

use App\Components\Output\Interfaces\OutputDriverInterface;
use App\Components\Output\Registry\OutputDriverRegistry;
use Illuminate\Foundation\Application;
use Webmozart\Assert\Assert;

class OutputDriverFactory
{
    public function __construct(private readonly OutputDriverRegistry $driverRegistry, private readonly Application $application)
    {
    }

    /**
     * @param string $driverName
     * @return OutputDriverInterface
     */
    public function createDriver(string $driverName) : OutputDriverInterface
    {
        $driverClass = $this->driverRegistry->fetchDriverClassByName($driverName);
        Assert::notNull($driverClass);

        // @todo implement proper factory
        /** @var OutputDriverInterface $driver */
        $driver = $this->application->make($driverClass);
        Assert::implementsInterface($driver, OutputDriverInterface::class);

        return $driver;
    }
}

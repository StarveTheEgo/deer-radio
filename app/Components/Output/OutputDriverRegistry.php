<?php

declare(strict_types=1);

namespace App\Components\Output;

use App\Components\Output\Interfaces\OutputDriverInterface;
use InvalidArgumentException;

class OutputDriverRegistry
{
    /** @var array<class-string<OutputDriverInterface>> */
    private array $drivers = [];

    /**
     * @param class-string<OutputDriverInterface> $driverClass
     * @return void
     */
    public function registerDriverClass(string $driverClass) : void
    {
        if (!is_subclass_of($driverClass, OutputDriverInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Expected driverClass to be a subclass of %s, but %s provided',
                OutputDriverInterface::class,
                $driverClass
            ));
        }

        $this->drivers[$driverClass::getTechnicalName()] = $driverClass;
    }

    /**
     * @return OutputDriverInterface[]|string[]
     */
    public function getDriverClasses() : array
    {
        return $this->drivers;
    }

    /**
     * @return array<string, string>
     */
    public function getDriverTitles() : array
    {
        $driverTitles = [];

        foreach ($this->drivers as $driverClass) {
            $driverTitles[$driverClass::getTechnicalName()] = $driverClass::getTitle();
        }

        return $driverTitles;
    }

    /**
     * @param string $technicalName
     * @return class-string<OutputDriverInterface>|null
     */
    public function fetchDriverClassByName(string $technicalName) : ?string
    {
        return $this->drivers[$technicalName] ?? null;
    }
}

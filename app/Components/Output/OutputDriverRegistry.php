<?php

declare(strict_types=1);

namespace App\Components\Output;

use App\Components\Output\Interfaces\OutputDriverInterface;
use InvalidArgumentException;

class OutputDriverRegistry
{
    /** @var string[]|OutputDriverInterface[]  */
    private array $drivers = [];

    /**
     * @param string $driverClass
     * @return void
     */
    public function registerDriverClass(string $driverClass) : void
    {
        if (!is_subclass_of($driverClass, OutputDriverInterface::class)) {
            throw new InvalidArgumentException();
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
}

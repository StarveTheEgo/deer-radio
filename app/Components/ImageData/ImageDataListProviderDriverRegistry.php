<?php

declare(strict_types=1);

namespace App\Components\ImageData;

use App\Components\ImageData\Driver\ImageDataListProviderInterface;
use LogicException;

class ImageDataListProviderDriverRegistry
{
    /** @var ImageDataListProviderInterface[] */
    private array $drivers = [];

    public function registerDriver(ImageDataListProviderInterface $driver): void
    {
        $this->drivers[$driver::getName()] = $driver;
    }

    public function getDriverNames(): array
    {
        $driverNames = array_keys($this->drivers);
        return array_combine($driverNames, $driverNames);
    }

    public function getDriver(string $driverName): ImageDataListProviderInterface
    {
        if (!array_key_exists($driverName, $this->drivers)) {
            throw new LogicException(sprintf('There is no driver with name "%s"', $driverName));
        }

        return $this->drivers[$driverName];
    }
}

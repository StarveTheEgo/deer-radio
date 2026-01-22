<?php

declare(strict_types=1);

namespace App\Components\ComponentData;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Service\ComponentDataAccessService;

class ComponentDataAccessor
{
    public function __construct(private readonly ComponentDataAccessService $service, private readonly string $component)
    {
    }

    public function getValue(string $field)
    {
        return $this->service->getValue($this->component, $field);
    }

    public function setValue(string $field, $value): ComponentData
    {
        return $this->service->setValue($this->component, $field, $value);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\ComponentData;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Service\ComponentDataAccessService;

class ComponentDataAccessor
{
    private ComponentDataAccessService $service;
    private string $component;

    public function __construct(ComponentDataAccessService $service, string $component)
    {
        $this->service = $service;
        $this->component = $component;
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

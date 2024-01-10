<?php

declare(strict_types=1);

namespace App\Components\ComponentData\Service;

use App\Components\ComponentData\ComponentDataAccessor;
use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Repository\ComponentDataRepositoryInterface;

class ComponentDataAccessService
{
    private ComponentDataRepositoryInterface $repository;

    public function __construct(ComponentDataRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getValue(string $component, string $field)
    {
        return $this->repository->getValue($component, $field);
    }

    public function setValue(string $component, string $field, $value): ComponentData
    {
        return $this->repository->setValue($component, $field, $value);
    }

    public function buildAccessor(string $component): ComponentDataAccessor
    {
        return new ComponentDataAccessor($this, $component);
    }
}

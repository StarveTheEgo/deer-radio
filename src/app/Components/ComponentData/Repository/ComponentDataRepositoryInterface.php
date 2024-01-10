<?php

declare(strict_types=1);

namespace App\Components\ComponentData\Repository;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\DoctrineOrchid\Repository\RepositoryInterface;

interface ComponentDataRepositoryInterface extends RepositoryInterface
{
    public function create(ComponentData $componentData);

    public function update(ComponentData $componentData): void;

    public function findOne(string $component, string $field): ?ComponentData;

    public function getValue(string $component, string $field);

    public function setValue(string $component, string $field, $value): ComponentData;
}

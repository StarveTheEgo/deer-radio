<?php

declare(strict_types=1);

namespace App\Components\ComponentData\Repository;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\DoctrineOrchid\Repository\AbstractRepository;

class ComponentDataRepository extends AbstractRepository implements ComponentDataRepositoryInterface
{
    public function create(ComponentData $componentData): void
    {
        parent::createObject($componentData);
    }

    public function findOne(string $component, string $field): ?ComponentData
    {
        /** @var ComponentData|null $componentData */
        $componentData = $this->getEntityRepository()->findOneBy(['component' => $component, 'field' => $field]);

        return $componentData;
    }

    public function update(ComponentData $componentData): void
    {
        parent::updateObject($componentData);
    }

    public function getValue(string $component, string $field)
    {
        $componentData = $this->findOne($component, $field);

        return $this->parseValue($componentData?->getValue());
    }

    public function parseValue($value)
    {
        if (null === $value) {
            return null;
        }

        return unserialize($value, ['allowed_classes' => true]);
    }

    public function setValue(string $component, string $field, $value): ComponentData
    {
        $serializedValue = $this->serializeValue($value);
        $componentData = $this->findOne($component, $field);
        if (null !== $componentData) {
            $componentData->setValue($serializedValue);
            $this->update($componentData);

            return $componentData;
        }

        // data does not exist yet
        $componentData = new ComponentData();
        $componentData->setComponent($component);
        $componentData->setField($field);
        $componentData->setValue($serializedValue);

        $this->create($componentData);

        return $componentData;
    }

    public function serializeValue($value)
    {
        if (null === $value) {
            return null;
        }

        return serialize($value);
    }
}

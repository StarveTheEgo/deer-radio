<?php

namespace App\Components\Setting\Orchid\Field;

use LogicException;

class FieldFactoryRegistry
{
    /** @var FieldFactoryInterface[] */
    private array $factories = [];

    public function registerFactory(FieldFactoryInterface $factory)
    {
        $fieldTypeValue = $factory::getType()->value;
        $this->factories[$fieldTypeValue] = $factory;
    }

    public function getTypeValues(): array
    {
        return array_keys($this->factories);
    }

    public function getTypeTitles(): array
    {
        $result = [];
        foreach ($this->factories as $factory) {
            $fieldType = $factory::getType();
            $result[$fieldType->value] = $fieldType->title();
        }
        return $result;
    }

    public function getFactory(FieldType $fieldType): FieldFactoryInterface
    {
        $fieldTypeValue = $fieldType->value;
        if (!array_key_exists($fieldTypeValue, $this->factories)) {
            throw new LogicException(sprintf('There is no factory for field type "%s"', $fieldTypeValue));
        }

        return $this->factories[$fieldTypeValue];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Registry;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Interface\FieldFactoryInterface;
use App\Components\OrchidIntergration\Interface\FieldOptionsFactoryInterface;
use LogicException;

class FieldFactoryRegistry
{
    /** @var array<string, FieldFactoryInterface> */
    private array $fieldFactories = [];

    /** @var array<string, FieldOptionsFactoryInterface> */
    private array $fieldOptionsFactories = [];

    /**
     * @param FieldType $fieldType
     * @param FieldFactoryInterface $fieldFactory
     * @return void
     */
    public function registerFieldFactory(FieldType $fieldType, FieldFactoryInterface $fieldFactory): void
    {
        $this->fieldFactories[$fieldType->value] = $fieldFactory;
    }

    /**
     * @param FieldType $fieldType
     * @param FieldOptionsFactoryInterface $optionsFactory
     * @return void
     */
    public function registerFieldOptionsFactory(FieldType $fieldType, FieldOptionsFactoryInterface $optionsFactory): void
    {
        $this->fieldOptionsFactories[$fieldType->value] = $optionsFactory;
    }

    public function getFieldTypeValues(): array
    {
        return array_keys($this->fieldFactories);
    }

    public function getFieldTypeTitles(): array
    {
        $result = [];
        foreach ($this->fieldFactories as $factory) {
            $fieldType = $factory::getType();
            $result[$fieldType->value] = $fieldType->title();
        }
        return $result;
    }

    public function getFieldFactory(FieldType $fieldType): FieldFactoryInterface
    {
        $fieldTypeValue = $fieldType->value;
        if (!array_key_exists($fieldTypeValue, $this->fieldFactories)) {
            throw new LogicException(sprintf('There is no field factory for field type "%s"', $fieldTypeValue));
        }

        return $this->fieldFactories[$fieldTypeValue];
    }

    public function getFieldOptionsFactory(FieldType $fieldType): FieldOptionsFactoryInterface
    {
        $fieldTypeValue = $fieldType->value;
        if (!array_key_exists($fieldTypeValue, $this->fieldOptionsFactories)) {
            throw new LogicException(sprintf('There is no field option factory for field type "%s"', $fieldTypeValue));
        }

        return $this->fieldOptionsFactories[$fieldTypeValue];
    }
}

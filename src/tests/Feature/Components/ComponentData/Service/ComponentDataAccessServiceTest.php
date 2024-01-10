<?php

namespace Components\ComponentData\Service;

use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Repository\ComponentDataRepository;
use App\Components\ComponentData\Service\ComponentDataAccessService;
use PHPUnit\Framework\TestCase;

class ComponentDataAccessServiceTest extends TestCase
{
    private function getRepositoryMock(?ComponentData $existingComponentData): ComponentDataRepository
    {
        $repository = $this->createPartialMock(ComponentDataRepository::class, [
            'create',
            'update',
            'findOne',
        ]);

        $repository->expects($this->any())
            ->method('findOne')
            ->willReturn($existingComponentData);

        return $repository;
    }

    private function createComponentData(string $component, string $field): ComponentData
    {
        $componentData = new ComponentData();
        $componentData->setComponent($component);
        $componentData->setField($field);
        return $componentData;
    }

    /**
     * @return void
     * @dataProvider getDataProvider
     */
    public function testGetValue(string $component, string $field, $expectedValue, bool $hasExistingData)
    {
        if ($hasExistingData) {
            $existingComponentData = $this->createComponentData($component, $field);
        } else {
            $existingComponentData = null;
        }

        $repository = $this->getRepositoryMock($existingComponentData);
        if ($hasExistingData) {
            // @todo refactor
            $existingComponentData->setValue($repository->serializeValue($expectedValue));
        }
        $service = new ComponentDataAccessService($repository);
        $this->assertEquals($expectedValue, $service->getValue($component, $field));
    }

    public function getDataProvider()
    {
        return [
            'withoutExistingData' => [
                'component' => 'component_1',
                'field' => 'field_1',
                'value' => null,
                'hasExistingData' => false,
            ],
            'withExistingData' => [
                'component' => 'component_2',
                'field' => 'field_2',
                'value' => 'value_2',
                'hasExistingData' => true,
            ],
            'withExistingDataNullValue' => [
                'component' => 'component_3',
                'field' => 'field_3',
                'value' => null,
                'hasExistingData' => true,
            ],
            'withExistingDataArrayValue' => [
                'component' => 'component_3',
                'field' => 'field_3',
                'value' => ['deer' => 'door'],
                'hasExistingData' => true,
            ],
        ];
    }

    /**
     * @return void
     *
     * @dataProvider setDataProvider
     */
    public function testSetValue(string $component, string $field, $value, bool $hasExistingData)
    {
        if ($hasExistingData) {
            $existingComponentData = $this->createComponentData($component, $field);
        } else {
            $existingComponentData = null;
        }

        $repository = $this->getRepositoryMock($existingComponentData);
        $service = new ComponentDataAccessService($repository);

        $updatedComponentData = $service->setValue($component, $field, $value);
        $this->assertEquals($value, $repository->parseValue($updatedComponentData->getValue()));
    }

    public function setDataProvider()
    {
        return [
            'withoutExistingData' => [
                'component' => 'component_1',
                'field' => 'field_1',
                'value' => 'value_1',
                'hasExistingData' => false,
            ],
            'withExistingData' => [
                'component' => 'component_2',
                'field' => 'field_2',
                'value' => 'value_2',
                'hasExistingData' => true,
            ],
            'withExistingDataNullValue' => [
                'component' => 'component_3',
                'field' => 'field_3',
                'value' => null,
                'hasExistingData' => true,
            ],
            'withExistingDataArrayValue' => [
                'component' => 'component_3',
                'field' => 'field_3',
                'value' => ['deer' => 'door'],
                'hasExistingData' => true,
            ],
        ];
    }
}

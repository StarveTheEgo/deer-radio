<?php

declare(strict_types=1);

namespace App\Components\Setting\Service;

use App\Components\DoctrineOrchid\Filter\AbstractDoctrineFilter;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Repository\SettingRepositoryInterface;

class SettingReadService
{
    private SettingRepositoryInterface $repository;
    private SettingValueService $valueService;

    /**
     * @param SettingRepositoryInterface $repository
     * @param SettingValueService $valueService
     */
    public function __construct(SettingRepositoryInterface $repository, SettingValueService $valueService)
    {
        $this->repository = $repository;
        $this->valueService = $valueService;
    }

    /**
     * @param array<AbstractDoctrineFilter> $filters
     * @return array<Setting>
     */
    public function filteredFindAll(array $filters): array
    {
        return $this->repository->filteredFindAll($filters);
    }

    /**
     * @param string $key
     * @return Setting|null
     */
    public function findByKey(string $key): ?Setting
    {
        return $this->repository->findByKey($key);
    }

    /**
     * @param string $key
     * @param string|null $defaultValue
     * @return string|null
     */
    public function getValue(string $key, string $defaultValue = null): ?string
    {
        $setting = $this->repository->findByKey($key);
        if ($setting === null) {
            return $defaultValue;
        }
        return $this->valueService->getValue($setting);
    }
}

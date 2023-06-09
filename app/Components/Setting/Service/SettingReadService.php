<?php

declare(strict_types=1);

namespace App\Components\Setting\Service;

use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Repository\SettingRepositoryInterface;

class SettingReadService
{
    private SettingRepositoryInterface $repository;
    private SettingValueService $valueService;

    public function __construct(SettingRepositoryInterface $repository, SettingValueService $valueService)
    {
        $this->repository = $repository;
        $this->valueService = $valueService;
    }

    public function filteredFindAll(array $filters)
    {
        return $this->repository->filteredFindAll($filters);
    }

    public function findByKey(string $key): ?Setting
    {
        return $this->repository->findByKey($key);
    }

    public function getValue(string $key, string $defaultValue = null): ?string
    {
        $setting = $this->repository->findByKey($key);
        if ($setting === null) {
            return $defaultValue;
        }
        return $this->valueService->getValue($setting);
    }
}

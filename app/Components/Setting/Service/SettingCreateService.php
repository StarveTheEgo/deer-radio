<?php

declare(strict_types=1);

namespace App\Components\Setting\Service;

use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Repository\SettingRepositoryInterface;

class SettingCreateService
{
    private SettingRepositoryInterface $repository;

    public function __construct(SettingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(Setting $setting): void
    {
        $this->repository->create($setting);
    }
}

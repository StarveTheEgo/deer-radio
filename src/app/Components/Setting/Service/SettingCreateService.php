<?php

declare(strict_types=1);

namespace App\Components\Setting\Service;

use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Repository\SettingRepositoryInterface;

class SettingCreateService
{
    public function __construct(private readonly SettingRepositoryInterface $repository)
    {
    }

    public function create(Setting $setting): void
    {
        $this->repository->create($setting);
    }
}

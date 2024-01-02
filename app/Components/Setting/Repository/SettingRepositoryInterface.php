<?php

declare(strict_types=1);

namespace App\Components\Setting\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Setting\Entity\Setting;

interface SettingRepositoryInterface extends RepositoryInterface
{
    public function create(Setting $setting) : void;

    public function findByKey(string $key): ?Setting;

    public function update(Setting $setting): void;

    public function delete(Setting $setting): void;
}

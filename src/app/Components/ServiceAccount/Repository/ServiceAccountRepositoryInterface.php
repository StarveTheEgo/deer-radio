<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\ServiceAccount\Entity\ServiceAccount;

interface ServiceAccountRepositoryInterface extends RepositoryInterface
{
    public function create(ServiceAccount $ServiceAccount);

    public function update(ServiceAccount $ServiceAccount): void;

    public function delete(ServiceAccount $ServiceAccount): void;
}

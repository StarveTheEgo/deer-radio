<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\ServiceAccount\Entity\ServiceAccount;

class ServiceAccountRepository extends AbstractRepository implements ServiceAccountRepositoryInterface
{
    public function create(ServiceAccount $ServiceAccount): void
    {
        parent::createObject($ServiceAccount);
    }

    public function update(ServiceAccount $ServiceAccount): void
    {
        parent::updateObject($ServiceAccount);
    }

    public function delete(ServiceAccount $ServiceAccount): void
    {
        parent::deleteObject($ServiceAccount);
    }
}

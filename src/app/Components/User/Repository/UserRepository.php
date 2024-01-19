<?php

declare(strict_types=1);

namespace App\Components\User\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\User\Entity\User;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function create(User $User): void
    {
        parent::createObject($User);
    }

    public function update(User $User): void
    {
        parent::updateObject($User);
    }

    public function delete(User $User): void
    {
        parent::deleteObject($User);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\User\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\User\Entity\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function create(User $User);

    public function update(User $User): void;

    public function delete(User $User): void;
}

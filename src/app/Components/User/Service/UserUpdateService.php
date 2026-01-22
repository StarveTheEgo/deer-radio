<?php

declare(strict_types=1);

namespace App\Components\User\Service;

use App\Components\User\Entity\User;
use App\Components\User\Repository\UserRepositoryInterface;

class UserUpdateService
{
    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    /**
     * @param User $User
     * @return void
     */
    public function update(User $User): void
    {
        $this->repository->update($User);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\User\Service;

use App\Components\User\Entity\User;
use App\Components\User\Repository\UserRepositoryInterface;

class UserCreateService
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(User $User): void
    {
        $this->repository->create($User);
    }
}

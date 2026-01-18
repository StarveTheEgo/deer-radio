<?php

declare(strict_types=1);

namespace App\Components\User\Service;

use App\Components\User\Entity\User;
use App\Components\User\Repository\UserRepositoryInterface;

class UserCreateService
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function create(User $User): void
    {
        $this->repository->create($User);
    }
}

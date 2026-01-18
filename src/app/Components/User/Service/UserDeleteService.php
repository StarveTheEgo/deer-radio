<?php

declare(strict_types=1);

namespace App\Components\User\Service;

use App\Components\User\Entity\User;
use App\Components\User\Repository\UserRepositoryInterface;

class UserDeleteService
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function delete(User $User): void
    {
        $this->repository->delete($User);
    }
}

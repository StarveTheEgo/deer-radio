<?php

declare(strict_types=1);

namespace App\Components\Photoban\Service;

use App\Components\Photoban\Repository\PhotobanRepositoryInterface;
use App\Components\Setting\Entity\Setting;

class PhotobanUpdateService
{
    private PhotobanRepositoryInterface $repository;

    public function __construct(PhotobanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(Setting $setting): void
    {
        $this->repository->update($setting);
    }
}

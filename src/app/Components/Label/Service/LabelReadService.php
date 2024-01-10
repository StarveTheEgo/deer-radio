<?php

declare(strict_types=1);

namespace App\Components\Label\Service;

use App\Components\Label\Repository\LabelRepositoryInterface;

class LabelReadService
{
    private LabelRepositoryInterface $repository;

    public function __construct(LabelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

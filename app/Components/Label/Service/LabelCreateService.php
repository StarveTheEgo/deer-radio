<?php

declare(strict_types=1);

namespace App\Components\Label\Service;

use App\Components\Label\Entity\Label;
use App\Components\Label\Repository\LabelRepositoryInterface;

class LabelCreateService
{
    private LabelRepositoryInterface $repository;

    public function __construct(LabelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(Label $label): void
    {
        $this->repository->create($label);
    }
}

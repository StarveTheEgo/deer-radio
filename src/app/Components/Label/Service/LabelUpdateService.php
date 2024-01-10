<?php

declare(strict_types=1);

namespace App\Components\Label\Service;

use App\Components\Label\Entity\Label;
use App\Components\Label\Repository\LabelRepositoryInterface;

class LabelUpdateService
{
    private LabelRepositoryInterface $repository;

    public function __construct(LabelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(Label $label): void
    {
        $this->repository->update($label);
    }
}

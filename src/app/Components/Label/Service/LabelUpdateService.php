<?php

declare(strict_types=1);

namespace App\Components\Label\Service;

use App\Components\Label\Entity\Label;
use App\Components\Label\Repository\LabelRepositoryInterface;

class LabelUpdateService
{
    public function __construct(private readonly LabelRepositoryInterface $repository)
    {
    }

    public function update(Label $label): void
    {
        $this->repository->update($label);
    }
}

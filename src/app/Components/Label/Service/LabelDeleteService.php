<?php

declare(strict_types=1);

namespace App\Components\Label\Service;

use App\Components\Label\Entity\Label;
use App\Components\Label\Repository\LabelRepositoryInterface;

class LabelDeleteService
{
    public function __construct(private readonly LabelRepositoryInterface $repository)
    {
    }

    public function delete(Label $label): void
    {
        $this->repository->delete($label);
    }
}

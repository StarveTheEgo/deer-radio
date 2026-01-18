<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepositoryInterface;

class OutputCreateService
{
    public function __construct(private readonly OutputRepositoryInterface $repository)
    {
    }

    public function create(Output $Output): void
    {
        $this->repository->create($Output);
    }
}

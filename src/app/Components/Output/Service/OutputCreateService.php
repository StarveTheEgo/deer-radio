<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepositoryInterface;

class OutputCreateService
{
    private OutputRepositoryInterface $repository;

    public function __construct(OutputRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(Output $Output): void
    {
        $this->repository->create($Output);
    }
}

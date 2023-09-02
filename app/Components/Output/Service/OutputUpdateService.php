<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepositoryInterface;

class OutputUpdateService
{
    private OutputRepositoryInterface $repository;

    public function __construct(OutputRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(Output $Output): void
    {
        $this->repository->update($Output);
    }
}

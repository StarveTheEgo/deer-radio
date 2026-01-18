<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepositoryInterface;

class OutputUpdateService
{
    /**
     * @param OutputRepositoryInterface $repository
     */
    public function __construct(private readonly OutputRepositoryInterface $repository)
    {
    }

    /**
     * @param Output $Output
     * @return void
     */
    public function update(Output $Output): void
    {
        $this->repository->update($Output);
    }
}

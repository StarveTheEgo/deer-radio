<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

use App\Components\Output\Entity\Output;
use App\Components\Output\Repository\OutputRepositoryInterface;

class OutputDeleteService
{
    public function __construct(private readonly OutputRepositoryInterface $repository)
    {
    }

    public function delete(Output $Output): void
    {
        $this->repository->delete($Output);
    }
}

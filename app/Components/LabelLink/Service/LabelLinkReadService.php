<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Service;

use App\Components\LabelLink\Repository\LabelLinkRepositoryInterface;

class LabelLinkReadService
{
    private LabelLinkRepositoryInterface $repository;

    public function __construct(LabelLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

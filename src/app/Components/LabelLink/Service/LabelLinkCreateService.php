<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Service;

use App\Components\LabelLink\Entity\LabelLink;
use App\Components\LabelLink\Repository\LabelLinkRepositoryInterface;

class LabelLinkCreateService
{
    private LabelLinkRepositoryInterface $repository;

    public function __construct(LabelLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(LabelLink $labelLink): void
    {
        $this->repository->create($labelLink);
    }
}

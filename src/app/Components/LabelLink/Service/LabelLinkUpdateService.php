<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Service;

use App\Components\LabelLink\Entity\LabelLink;
use App\Components\LabelLink\Repository\LabelLinkRepositoryInterface;

class LabelLinkUpdateService
{
    public function __construct(private readonly LabelLinkRepositoryInterface $repository)
    {
    }

    public function update(LabelLink $labelLink): void
    {
        $this->repository->update($labelLink);
    }
}

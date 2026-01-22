<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Service;

use App\Components\LabelLink\Repository\LabelLinkRepositoryInterface;

class LabelLinkReadService
{
    public function __construct(private readonly LabelLinkRepositoryInterface $repository)
    {
    }
}

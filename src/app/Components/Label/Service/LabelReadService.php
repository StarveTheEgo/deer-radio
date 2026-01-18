<?php

declare(strict_types=1);

namespace App\Components\Label\Service;

use App\Components\Label\Repository\LabelRepositoryInterface;

class LabelReadService
{
    public function __construct(private readonly LabelRepositoryInterface $repository)
    {
    }
}

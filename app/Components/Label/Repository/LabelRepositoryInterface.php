<?php

declare(strict_types=1);

namespace App\Components\Label\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Label\Entity\Label;

interface LabelRepositoryInterface extends RepositoryInterface
{
    public function create(Label $label);

    public function update(Label $label): void;

    public function delete(Label $label): void;
}

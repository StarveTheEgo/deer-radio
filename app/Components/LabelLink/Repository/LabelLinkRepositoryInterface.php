<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\LabelLink\Entity\LabelLink;

interface LabelLinkRepositoryInterface extends RepositoryInterface
{
    public function create(LabelLink $labelLink);

    public function update(LabelLink $labelLink): void;

    public function delete(LabelLink $labelLink): void;
}

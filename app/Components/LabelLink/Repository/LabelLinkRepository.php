<?php

declare(strict_types=1);

namespace App\Components\LabelLink\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\LabelLink\Entity\LabelLink;

class LabelLinkRepository extends AbstractRepository implements LabelLinkRepositoryInterface
{
    public function create(LabelLink $labelLink): void
    {
        parent::createObject($labelLink);
    }

    public function update(LabelLink $labelLink): void
    {
        parent::updateObject($labelLink);
    }

    public function delete(LabelLink $labelLink): void
    {
        parent::deleteObject($labelLink);
    }
}

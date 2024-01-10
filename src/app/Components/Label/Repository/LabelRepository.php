<?php

declare(strict_types=1);

namespace App\Components\Label\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Label\Entity\Label;

class LabelRepository extends AbstractRepository implements LabelRepositoryInterface
{
    public function create(Label $label): void
    {
        parent::createObject($label);
    }

    public function update(Label $label): void
    {
        parent::updateObject($label);
    }

    public function delete(Label $label): void
    {
        parent::deleteObject($label);
    }
}

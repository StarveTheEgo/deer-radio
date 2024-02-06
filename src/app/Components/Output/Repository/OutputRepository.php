<?php

declare(strict_types=1);

namespace App\Components\Output\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Output\Entity\Output;

class OutputRepository extends AbstractRepository implements OutputRepositoryInterface
{
    public function create(Output $Output): void
    {
        parent::createObject($Output);
    }

    public function update(Output $Output): void
    {
        parent::updateObject($Output);
    }

    public function delete(Output $Output): void
    {
        parent::deleteObject($Output);
    }

    /**
     * @return array<Output>
     */
    public function getAllActiveOutputs(): array
    {
        return $this->getEntityRepository()->findBy([
            'isActive' => true,
        ]);
    }
}

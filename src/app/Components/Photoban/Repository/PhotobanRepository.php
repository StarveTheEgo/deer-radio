<?php

declare(strict_types=1);

namespace App\Components\Photoban\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Photoban\Entity\Photoban;

class PhotobanRepository extends AbstractRepository implements PhotobanRepositoryInterface
{
    public function findByUrl(string $imageUrl): ?Photoban
    {
        /** @var Photoban|null $photoban */
        $photoban = $this->getEntityRepository()->findOneBy(['imageUrl' => $imageUrl]);
        return $photoban;
    }

    public function create(Photoban $photoban): void
    {
        parent::createObject($photoban);
    }

    public function update(Photoban $photoban): void
    {
        parent::updateObject($photoban);
    }

    public function delete(Photoban $photoban): void
    {
        parent::deleteObject($photoban);
    }
}

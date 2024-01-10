<?php

declare(strict_types=1);

namespace App\Components\Photoban\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Photoban\Entity\Photoban;

interface PhotobanRepositoryInterface extends RepositoryInterface
{
    public function create(Photoban $photoban);

    public function findByUrl(string $imageUrl): ?Photoban;

    public function update(Photoban $photoban): void;

    public function delete(Photoban $photoban): void;
}

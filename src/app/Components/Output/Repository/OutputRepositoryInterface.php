<?php

declare(strict_types=1);

namespace App\Components\Output\Repository;

use App\Components\DoctrineOrchid\Repository\RepositoryInterface;
use App\Components\Output\Entity\Output;

interface OutputRepositoryInterface extends RepositoryInterface
{
    public function create(Output $Output);

    public function update(Output $Output): void;

    public function delete(Output $Output): void;

    /**
     * Gets all the active outputs
     * @return array<Output>
     */
    public function getAllActiveOutputs() : array;
}

<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid;

use DateTimeImmutable;

interface TimestampableInterface {
    public function getCreatedAt(): ?DateTimeImmutable;

    public function setCreatedAt(?DateTimeImmutable $createdAt): self;

    public function getUpdatedAt(): ?DateTimeImmutable;

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self;
}

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Trait;

trait HasDescriptionTrait
{
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}

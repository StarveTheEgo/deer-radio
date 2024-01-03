<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Trait;

trait HasValidationTrait
{
    private ?array $validation = null;

    public function getValidation(): ?array
    {
        return $this->validation;
    }

    public function setValidation(?array $validation): self
    {
        $this->validation = $validation;

        return $this;
    }
}

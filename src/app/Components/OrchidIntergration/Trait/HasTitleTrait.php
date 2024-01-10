<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Trait;

trait HasTitleTrait
{
    private ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}

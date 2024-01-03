<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Input\FieldOptions;

use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use App\Components\OrchidIntergration\Trait\HasDescriptionTrait;
use App\Components\OrchidIntergration\Trait\HasTitleTrait;
use App\Components\OrchidIntergration\Trait\HasValidationTrait;

final class InputOptions implements FieldOptionsInterface
{
    use HasTitleTrait;
    use HasValidationTrait;
    use HasDescriptionTrait;

    private string $type = 'text';

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function toArray() : array
    {
        return [
            'title' => $this->getTitle(),
            'type' => $this->getType(),
            'description' => $this->getDescription(),
            'validation' => $this->getValidation(),
        ];
    }
}

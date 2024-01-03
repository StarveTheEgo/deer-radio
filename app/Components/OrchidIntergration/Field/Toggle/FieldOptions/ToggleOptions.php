<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Toggle\FieldOptions;

use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use App\Components\OrchidIntergration\Trait\HasDescriptionTrait;
use App\Components\OrchidIntergration\Trait\HasTitleTrait;
use App\Components\OrchidIntergration\Trait\HasValidationTrait;

final class ToggleOptions implements FieldOptionsInterface
{
    use HasTitleTrait;
    use HasValidationTrait;
    use HasDescriptionTrait;

    public function toArray() : array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'validation' => $this->getValidation(),
        ];
    }
}

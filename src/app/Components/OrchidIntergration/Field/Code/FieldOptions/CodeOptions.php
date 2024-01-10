<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Code\FieldOptions;

use App\Components\OrchidIntergration\Interface\FieldOptionsInterface;
use App\Components\OrchidIntergration\Trait\HasTitleTrait;
use App\Components\OrchidIntergration\Trait\HasValidationTrait;
use Orchid\Screen\Fields\Code;

final class CodeOptions implements FieldOptionsInterface
{
    use HasTitleTrait;
    use HasValidationTrait;

    private string $language = Code::JS;

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): CodeOptions
    {
        $this->language = $language;

        return $this;
    }

    public function toArray() : array
    {
        return [
            'title' => $this->getTitle(),
            'language' => $this->getLanguage(),
            'validation' => $this->getValidation(),
        ];
    }
}

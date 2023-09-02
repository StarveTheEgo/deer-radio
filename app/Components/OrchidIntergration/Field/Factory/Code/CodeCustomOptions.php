<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Factory\Code;

use LogicException;
use Orchid\Screen\Fields\Code;

final class CodeCustomOptions
{
    private string $language = Code::JS;

    public static function fromArray(array $input): CodeCustomOptions
    {
        $options = new self();

        if (array_key_exists('language', $input)) {
            $options->setLanguage($input['language']);
            unset($input['language']);
        }

        if (!empty($input)) {
            throw new LogicException(sprintf('Unexpected option fields: %s', implode(', ', array_keys($input))));
        }

        return $options;
    }

    public function toArray() : array {
        return [
            'language' => $this->getLanguage(),
        ];
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): CodeCustomOptions
    {
        $this->language = $language;

        return $this;
    }
}

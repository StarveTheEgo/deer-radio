<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Factory\Toggle;

use LogicException;

final class ToggleCustomOptions
{
    private ?string $description = null;

    public static function fromArray(array $input): ToggleCustomOptions
    {
        $options = new self();

        if (array_key_exists('description', $input)) {
            $options->setDescription($input['description']);
            unset($input['description']);
        }

        if (!empty($input)) {
            throw new LogicException(sprintf('Unexpected option fields: %s', implode(', ', array_keys($input))));
        }

        return $options;
    }

    public function toArray() : array {
        return [
            'description' => $this->getDescription(),
        ];
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ToggleCustomOptions
    {
        $this->description = $description;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Factory\Input;

use LogicException;

final class InputCustomOptions
{
    private string $type = 'text';

    private ?string $description = null;

    public static function fromArray(array $input): InputCustomOptions
    {
        $options = new self();

        if (array_key_exists('type', $input)) {
            $options->setType($input['type']);
            unset($input['type']);
        }

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
            'type' => $this->getType(),
            'description' => $this->getDescription(),
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): InputCustomOptions
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): InputCustomOptions
    {
        $this->description = $description;

        return $this;
    }
}

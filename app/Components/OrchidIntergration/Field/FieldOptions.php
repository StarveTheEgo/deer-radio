<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field;

use LogicException;

final class FieldOptions
{
    private ?string $title = null;
    private ?array $validation = null;
    private ?array $custom = null;

    public static function fromArray(array $input): FieldOptions
    {
        $options = new self();

        if (array_key_exists('title', $input)) {
            $options->setTitle($input['title']);
            unset($input['title']);
        }

        if (array_key_exists('validation', $input)) {
            $options->setValidation($input['validation']);
            unset($input['validation']);
        }

        if (array_key_exists('custom', $input)) {
            $options->setCustom($input['custom']);
            unset($input['custom']);
        }

        if (!empty($input)) {
            throw new LogicException(sprintf('Unexpected option fields: %s', implode(', ', array_keys($input))));
        }

        return $options;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'validation' => $this->getValidation(),
            'custom' => $this->getCustom(),
        ];
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): FieldOptions
    {
        $this->title = $title;

        return $this;
    }

    public function getValidation(): ?array
    {
        return $this->validation;
    }

    public function setValidation(?array $validation): FieldOptions
    {
        $this->validation = $validation;

        return $this;
    }

    public function getCustom(): ?array
    {
        return $this->custom;
    }

    public function setCustom(?array $custom): FieldOptions
    {
        $this->custom = $custom;

        return $this;
    }
}

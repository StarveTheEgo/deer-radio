<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Interface;

interface FieldOptionsInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray() : array;

    /**
     * @return array<mixed>|null
     */
    public function getValidation() : ?array;
}

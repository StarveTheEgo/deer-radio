<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Interface;

interface FieldOptionsFactoryInterface
{
    /**
     * @param array<string, mixed> $input
     * @return FieldOptionsInterface
     */
    public function fromArray(array $input) : FieldOptionsInterface;
}

<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Interface;

use App\Components\OrchidIntergration\Enum\FieldType;
use Orchid\Screen\Field;

interface FieldFactoryInterface
{
    public static function getType(): FieldType;

    public function buildField(FieldOptionsInterface $options): Field;
}

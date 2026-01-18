<?php

namespace App\Orchid\Resources;

use App\Orchid\ResourceOrder;
use Orchid\Crud\Resource;

abstract class AbstractResource extends Resource
{
    #[\Override]
    public static function sort(): string
    {
        return ResourceOrder::getSortValueFor(static::class);
    }

    #[\Override]
    public static function trafficCop(): bool
    {
        return true;
    }

}

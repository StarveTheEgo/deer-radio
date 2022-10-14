<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Orchid\Resources\AlbumResource;
use App\Orchid\Resources\AuthorLinkResource;
use App\Orchid\Resources\AuthorResource;
use LogicException;

abstract class ResourceOrder
{
    private const BASE_SORT_VALUE = 2000;

    private const SORT_VALUES_MAP = [
        AuthorResource::class,
        AuthorLinkResource::class,
        AlbumResource::class,
    ];

    public static function getSortValueFor(string $resource_class_name): string
    {
        $sort_index = array_search($resource_class_name, self::SORT_VALUES_MAP);
        if (false === $sort_index) {
            throw new LogicException(sprintf('Class "%s" must be registered for sorting.', $resource_class_name));
        }

        return (string) (self::BASE_SORT_VALUE + $sort_index);
    }
}

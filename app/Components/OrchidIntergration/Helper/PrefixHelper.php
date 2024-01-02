<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Helper;

use LogicException;
use Orchid\Screen\Field;

class PrefixHelper
{
    /**
     * Adds required prefix to specified field names
     * @param array<Field> $fields
     * @return array<Field>
     */
    public static function addPrefixToFields(string $prefix, array $fields): array
    {
        return array_map(function (Field $field) use ($prefix) {
            return $field->set('name', $prefix.$field->get('name'));
        }, $fields);
    }

    /**
     * Adds required prefix to specified array keys
     * @param array<string, mixed> $array
     * @return array<string, mixed>
     */
    public static function addPrefixToArrayKeys(string $prefix, array $array): array
    {
        foreach ($array as $key => $value) {
            $newKey = $prefix.$key;
            if (array_key_exists($newKey, $array)) {
                throw new LogicException(sprintf('Key "%s" already exists', $newKey));
            }
            $array[$newKey] = $value;
            unset($array[$key]);
        }

        return $array;
    }
}

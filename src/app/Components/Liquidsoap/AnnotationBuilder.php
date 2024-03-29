<?php

declare(strict_types=1);

namespace App\Components\Liquidsoap;

use JsonException;

class AnnotationBuilder
{
    /**
     * @param string $path
     * @param array<string, mixed> $metadata
     * @return string
     * @throws JsonException
     */
    public function buildDataAnnotation(string $path, array $metadata) : string
    {
        if (empty($metadata)) {
            return $path;
        }

        $result = [];
        foreach ($metadata as $attribute => $value) {
            if ($value === null) {
                $value = '';
            }

            if (is_bool($value)) {
                $value = (int) $value;
            }

            if (!is_scalar($value)) {
                $value = json_encode($value, JSON_THROW_ON_ERROR);
            }

            $value = str_replace('"', '\\"', (string) $value);
            $result[]= sprintf('%s="%s"', $attribute, $value);
        }

        return sprintf('annotate:%s:%s', implode(',', $result), $path);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Parser;

use Illuminate\Validation\ValidationException;
use JsonException;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

final class SchemaBasedDataParser
{
    /**
     * @param array<mixed> $input
     * @param string $pathToSchema
     * @return array<mixed>
     * @throws JsonException
     * @throws ValidationException
     */
    public function parseData(array $input, string $pathToSchema) : array
    {
        $validator = new Validator();

        $schemaRef = ['$ref' => 'file://'.$pathToSchema];
        $validator->validate($input, $schemaRef, Constraint::CHECK_MODE_APPLY_DEFAULTS);

        $errors = $validator->getErrors();
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return $input;
    }

    /**
     * @param string $json
     * @param string $pathToSchema
     * @return array<mixed>
     * @throws ValidationException
     * @throws JsonException
     */
    public function fromJson(string $json, string $pathToSchema) : array
    {
        $optionsData = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        return $this->parseData($optionsData, $pathToSchema);
    }
}

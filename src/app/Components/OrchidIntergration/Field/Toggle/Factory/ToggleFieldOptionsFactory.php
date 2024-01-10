<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Toggle\Factory;

use App\Components\DeerRadio\Parser\SchemaBasedDataParser;
use App\Components\OrchidIntergration\Field\Toggle\FieldOptions\ToggleOptions;
use App\Components\OrchidIntergration\Interface\FieldOptionsFactoryInterface;
use Illuminate\Validation\ValidationException;
use JsonException;

final class ToggleFieldOptionsFactory implements FieldOptionsFactoryInterface
{
    private const SCHEMA_PATH =  __DIR__.'/../schema/optionsSchema.json';

    private SchemaBasedDataParser $dataParser;

    /**
     * @param SchemaBasedDataParser $dataParser
     */
    public function __construct(SchemaBasedDataParser $dataParser)
    {
        $this->dataParser = $dataParser;
    }

    /**
     * @param array<string, mixed> $input
     * @return ToggleOptions
     * @throws ValidationException
     * @throws JsonException
     */
    public function fromArray(array $input) : ToggleOptions
    {
        $parsedData = $this->dataParser->parseData($input, self::SCHEMA_PATH);
        $options = new ToggleOptions();

        $options->setTitle($parsedData['title']);
        $options->setDescription($parsedData['description']);
        $options->setValidation($parsedData['validation']);

        return $options;
    }
}

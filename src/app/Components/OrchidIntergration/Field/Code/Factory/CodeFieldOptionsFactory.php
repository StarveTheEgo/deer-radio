<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Field\Code\Factory;

use App\Components\DeerRadio\Parser\SchemaBasedDataParser;
use App\Components\OrchidIntergration\Field\Code\FieldOptions\CodeOptions;
use App\Components\OrchidIntergration\Interface\FieldOptionsFactoryInterface;
use Illuminate\Validation\ValidationException;
use JsonException;

final readonly class CodeFieldOptionsFactory implements FieldOptionsFactoryInterface
{
    private const string SCHEMA_PATH = __DIR__.'/../schema/optionsSchema.json';

    /**
     * @param SchemaBasedDataParser $dataParser
     */
    public function __construct(private SchemaBasedDataParser $dataParser)
    {
    }

    /**
     * @param array<string> $input
     * @return CodeOptions
     * @throws ValidationException
     * @throws JsonException
     */
    public function fromArray(array $input) : CodeOptions
    {
        $parsedData = $this->dataParser->parseData($input, self::SCHEMA_PATH);
        $options = new CodeOptions();

        $options->setTitle($parsedData['title']);
        $options->setValidation($parsedData['validation']);
        $options->setLanguage($parsedData['language']);

        return $options;
    }
}

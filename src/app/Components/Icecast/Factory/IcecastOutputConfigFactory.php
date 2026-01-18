<?php

declare(strict_types=1);

namespace App\Components\Icecast\Factory;

use App\Components\DeerRadio\Parser\SchemaBasedDataParser;
use App\Components\Google\Model\GoogleOutputConfig;
use App\Components\Icecast\Model\IcecastOutputConfig;
use Illuminate\Validation\ValidationException;
use JsonException;

class IcecastOutputConfigFactory
{
    private const string SCHEMA_PATH =  __DIR__.'/../schema/outputConfigSchema.json';

    /**
     * @param SchemaBasedDataParser $dataParser
     */
    public function __construct(private readonly SchemaBasedDataParser $dataParser)
    {
    }

    /**
     * @param array<string, mixed> $input
     * @return IcecastOutputConfig
     * @throws ValidationException
     * @throws JsonException
     */
    public function createFromArray(array $input) : IcecastOutputConfig
    {
        $parsedData = $this->dataParser->parseData($input, self::SCHEMA_PATH);

        return new IcecastOutputConfig()
            ->setHost($parsedData['host'])
            ->setPort($parsedData['port'])
            ->setUser($parsedData['user'])
            ->setPassword($parsedData['password']);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Google\Factory;

use App\Components\DeerRadio\Parser\SchemaBasedDataParser;
use App\Components\Google\Enum\LiveBroadcastPrivacyStatus;
use App\Components\Google\Model\GoogleOutputConfig;
use Illuminate\Validation\ValidationException;
use JsonException;

class GoogleOutputConfigFactory
{
    private const SCHEMA_PATH =  __DIR__.'/../schema/outputConfigSchema.json';

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
     * @return GoogleOutputConfig
     * @throws ValidationException
     * @throws JsonException
     */
    public function createFromArray(array $input) : GoogleOutputConfig
    {
        $parsedData = $this->dataParser->parseData($input, self::SCHEMA_PATH);

        return (new GoogleOutputConfig())
            ->setServiceAccountId($parsedData['serviceAccountId'])
            ->setChatEnabled($parsedData['chatEnabled'])
            ->setPrivacyStatus(LiveBroadcastPrivacyStatus::from($parsedData['privacyStatus']));
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Icecast\Output;

use App\Components\Icecast\Factory\IcecastOutputConfigFactory;
use App\Components\Output\Entity\Output;
use App\Components\Output\Enum\OutputStreamState;
use App\Components\Output\Interfaces\OutputDriverInterface;
use Illuminate\Validation\ValidationException;
use JsonException;

class IcecastOutputDriver implements OutputDriverInterface
{
    /**
     * @return string
     */
    public static function getTechnicalName(): string
    {
        return 'icecast2';
    }

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return 'Icecast2';
    }

    /**
     * @param IcecastOutputConfigFactory $configFactory
     */
    public function __construct(private readonly IcecastOutputConfigFactory $configFactory)
    {
    }

    /**
     * @param Output $output
     * @return void
     */
    public function prepareLiveStream(Output $output): void
    {
        // do nothing
    }

    /**
     * @param Output $output
     * @return array{
     *      host: string,
     *      port: int,
     *      user: string,
     *      password: string,
     * }
     * @throws ValidationException
     * @throws JsonException
     */
    public function getLiquidsoapPayload(Output $output): array
    {
        $config = $this->configFactory->createFromArray($output->getDriverConfig());

        return [
            'host' => $config->getHost(),
            'port' => $config->getPort(),
            'user' => $config->getUser(),
            'password' => $config->getPassword(),
        ];
    }

    /**
     * @param Output $output
     * @return OutputStreamState
     */
    public function getStreamState(Output $output) : OutputStreamState
    {
        return OutputStreamState::UNKNOWN;
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Components\DeerRadio\Service\LivestreamHealthChecker;
use App\Components\Liquidsoap\Api\LiquidsoapApi;
use App\Components\Output\Service\OutputReadService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Ensures livestream is alive within some restrictions
 */
class LivestreamKeepAlive extends Command
{
    use WithoutOverlapping;

    /** @var string */
    protected $signature = 'livestream:keep-alive';

    /** @var string */
    protected $description = 'Ensures liquidsoap is working properly';

    /**
     * @param LivestreamHealthChecker $healthChecker
     * @param LiquidsoapApi $liquidsoapApi
     * @param OutputReadService $outputReadService
     * @param LoggerInterface $logger
     * @return int
     * @throws GuzzleException
     * @throws JsonException
     */
    public function handle(
        LivestreamHealthChecker $healthChecker,
        LiquidsoapApi $liquidsoapApi,
        OutputReadService $outputReadService,
        LoggerInterface $logger
    ): int
    {
        // check output endpoints
        foreach ($outputReadService->getAllActiveOutputs() as $activeOutput) {
            try {
                $healthChecker->actualizeAndCheckActiveOutputState($activeOutput);
            } catch (Exception $exception) {
                // currently restarting all the outputs on any exception
                $logger->error((string) $exception);
                $liquidsoapApi->outputsInit();
                return SymfonyCommand::SUCCESS;
            }
        }

        // check liquidsoap outputs
        try {
            $healthChecker->checkLiquidsoapOutputsStates();
        } catch (Exception $exception) {
            $logger->error((string) $exception);
            $liquidsoapApi->outputsInit();
        }

        return SymfonyCommand::SUCCESS;
    }
}

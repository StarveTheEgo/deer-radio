<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Components\Liquidsoap\Api\LiquidsoapApi;
use App\Components\Liquidsoap\Enum\LiquidsoapSettingKey;
use App\Components\Output\Enum\OutputStreamState;
use App\Components\Output\Factory\OutputDriverFactory;
use App\Components\Output\Service\OutputReadService;
use App\Components\Output\Service\OutputUpdateService;
use App\Components\Setting\Service\SettingReadService;
use DateTimeImmutable;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Creates Liquidsoap user based on environment variables
 */
class LiquidsoapKeepAlive extends Command
{
    use WithoutOverlapping;

    /** @var string */
    protected $signature = 'liquidsoap:keep-alive';

    /** @var string */
    protected $description = 'Ensures liquidsoap is working properly';

    /**
     * @param LiquidsoapApi $liquidsoapApi
     * @param OutputReadService $outputReadService
     * @param OutputUpdateService $outputUpdateService
     * @param OutputDriverFactory $driverFactory
     * @param SettingReadService $settingReadService
     * @param LoggerInterface $logger
     * @return int
     * @throws GuzzleException
     * @throws JsonException
     */
    public function handle(
        LiquidsoapApi $liquidsoapApi,
        OutputReadService $outputReadService,
        OutputUpdateService $outputUpdateService,
        OutputDriverFactory $driverFactory,
        SettingReadService $settingReadService,
        LoggerInterface $logger
    ): int
    {
        $maxInactiveStreamDuration = (int) $settingReadService->getValue(LiquidsoapSettingKey::MAX_INACTIVE_STREAM_DURATION->value);

        $shouldRestart = false;
        foreach ($outputReadService->getAllActiveOutputs() as $activeOutput) {
            $driverName = $activeOutput->getDriverName();
            $driver = $driverFactory->createDriver($driverName);

            // get the stream state and store it
            $streamState = $driver->getStreamState($activeOutput);
            $activeOutput->setStreamState($streamState->value);
            $outputUpdateService->update($activeOutput);

            if ($streamState === OutputStreamState::LIVE) {
                continue;
            }

            if ($streamState === OutputStreamState::FINISHED) {
                $logger->warning(sprintf(
                    'Output#%s\'s stream  is finished, scheduling restart',
                    $activeOutput->getId()
                ));
                $shouldRestart = true;
                continue;
            }

            // for all other states we will schedule a restart if the stream did not start after specific amount of time after preparation
            $currentTime = new DateTimeImmutable();
            $lastPreparationTime = $activeOutput->getPreparedAt();
            if (
                $lastPreparationTime === null
                ||
                ($currentTime->diff($lastPreparationTime)->s) >= $maxInactiveStreamDuration
            ) {
                $logger->warning(sprintf(
                    'Output#%s\'s stream state is "%s". Too much time passed since the last preparation, scheduling restart',
                    $activeOutput->getId(),
                    $streamState->value
                ));
                $shouldRestart = true;
            }
        }

        if ($shouldRestart) {
            // restarting all the outputs
            $liquidsoapApi->outputsInit();
        }

        return SymfonyCommand::SUCCESS;
    }
}

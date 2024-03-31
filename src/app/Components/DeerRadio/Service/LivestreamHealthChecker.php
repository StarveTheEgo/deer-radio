<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\DeerRadio\Exception\BadOutputStateException;
use App\Components\Liquidsoap\Api\LiquidsoapApi;
use App\Components\Liquidsoap\Enum\LiquidsoapSettingKey;
use App\Components\Output\Entity\Output;
use App\Components\Output\Enum\OutputStreamState;
use App\Components\Output\Factory\OutputDriverFactory;
use App\Components\Output\Service\OutputReadService;
use App\Components\Output\Service\OutputUpdateService;
use App\Components\Setting\Service\SettingReadService;
use DateTimeImmutable;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Log\LoggerInterface;

class LivestreamHealthChecker
{
    private LiquidsoapApi $liquidsoapApi;

    private OutputReadService $outputReadService;

    private OutputUpdateService $outputUpdateService;

    private OutputDriverFactory $driverFactory;

    private SettingReadService $settingReadService;

    public function __construct(
        LiquidsoapApi $liquidsoapApi,
        OutputReadService $outputReadService,
        OutputUpdateService $outputUpdateService,
        OutputDriverFactory $driverFactory,
        SettingReadService $settingReadService,
        LoggerInterface $logger
    )
    {

        $this->liquidsoapApi = $liquidsoapApi;
        $this->outputReadService = $outputReadService;
        $this->outputUpdateService = $outputUpdateService;
        $this->driverFactory = $driverFactory;
        $this->settingReadService = $settingReadService;
        $this->logger = $logger;
    }

    /**
     * Checks the state of specified output
     * Assumes that output should be working by now
     * If output does not appear to be healthy - throws an exception
     * @param Output $activeOutput
     * @return void
     * @throws BadOutputStateException
     */
    public function actualizeAndCheckActiveOutputState(Output $activeOutput) : void
    {
        $maxInactiveStreamDuration = (int) $this->settingReadService->getValue(LiquidsoapSettingKey::MAX_INACTIVE_STREAM_DURATION->value);

        $driverName = $activeOutput->getDriverName();
        $driver = $this->driverFactory->createDriver($driverName);

        // get the stream state and store it
        // @todo status setting should be separated concern
        $streamState = $driver->getStreamState($activeOutput);
        $activeOutput->setStreamState($streamState->value);
        $this->outputUpdateService->update($activeOutput);

        if ($streamState === OutputStreamState::LIVE) {
            return;
        }

        if ($streamState === OutputStreamState::FINISHED) {
            throw new BadOutputStateException(sprintf(
                'Output#%s\'s stream  is finished',
                $activeOutput->getId()
            ));
        }

        // for all other states we will schedule a restart if the stream did not start after specific amount of time after preparation
        $currentTime = new DateTimeImmutable();
        $lastPreparationTime = $activeOutput->getPreparedAt();
        if (
            $lastPreparationTime === null
            ||
            ($currentTime->getTimestamp() - $lastPreparationTime->getTimestamp()) >= $maxInactiveStreamDuration
        ) {
            throw new BadOutputStateException(sprintf(
                'Output#%s\'s stream state is "%s". Too much time passed since the last preparation',
                $activeOutput->getId(),
                $streamState->value
            ));
        }
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws JsonException
     * @throws BadOutputStateException
     */
    public function checkLiquidsoapOutputsStates(): void
    {
        $states = $this->liquidsoapApi->outputsStates();
        if (empty($states)) {
            $activeOutputs = $this->outputReadService->getAllActiveOutputs();
            if (!empty($activeOutputs)) {
                throw new BadOutputStateException('Application has active outputs, while liquidsoap does not have any');
            }
        }

        foreach ($states as $streamName => $streamState) {
            // @todo probably should handle null-values too
            $isActive = $streamState['is_active'] ?? false;
            $isReady = $streamState['is_ready'] ?? false;
            $isStarted = $streamState['is_started'] ?? false;
            $isUp = $streamState['is_up'] ?? false;

            $isHealthy = ($isActive && $isReady && $isStarted && $isUp);
            if (!$isHealthy) {
                throw new BadOutputStateException(sprintf(
                    'Liquidsoap state for stream "%s" is unhealthy: %s',
                    $streamName,
                    var_export($streamState, true)
                ));
            }
        }
    }
}

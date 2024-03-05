<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\LiveStream;

use App\Components\Output\Factory\OutputDriverFactory;
use App\Components\Output\Interfaces\ChatClientAwareInterface;
use App\Components\Output\Interfaces\ChatClientInterface;
use App\Components\Output\Registry\OutputDriverRegistry;
use App\Components\Output\Service\OutputReadService;
use App\Components\Output\Service\OutputUpdateService;
use App\Http\Controllers\Controller;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Webmozart\Assert\Assert;

class DeerRadioLivestreamController extends Controller
{
    private ResponseFactory $responseFactory;

    private OutputReadService $outputReadService;

    private OutputUpdateService $outputUpdateService;

    private OutputDriverFactory $driverFactory;

    private OutputDriverRegistry $driverRegistry;

    private LoggerInterface $logger;

    /**
     * @param ResponseFactory $responseFactory
     * @param OutputReadService $outputReadService
     * @param OutputUpdateService $outputUpdateService
     * @param OutputDriverFactory $driverFactory
     * @param OutputDriverRegistry $driverRegistry
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResponseFactory $responseFactory,
        OutputReadService $outputReadService,
        OutputUpdateService $outputUpdateService,
        OutputDriverFactory $driverFactory,
        OutputDriverRegistry $driverRegistry,
        LoggerInterface $logger
    )
    {
        $this->responseFactory = $responseFactory;
        $this->outputReadService = $outputReadService;
        $this->outputUpdateService = $outputUpdateService;
        $this->driverFactory = $driverFactory;
        $this->driverRegistry = $driverRegistry;
        $this->logger = $logger;
    }

    /**
     * Prepares all the active outputs for streaming
     * @return JsonResponse
     */
    public function prepare() : JsonResponse
    {
        $outputSettings = [];
        foreach ($this->outputReadService->getAllActiveOutputs() as $activeOutput) {
            $driverName = $activeOutput->getDriverName();
            $driver = $this->driverFactory->createDriver($driverName);

            $driver->prepareLiveStream($activeOutput);

            // store the data of preparation
            $currentTime = new DateTimeImmutable();
            $activeOutput->setPreparedAt($currentTime);
            $this->outputUpdateService->update($activeOutput);

            $payload = $driver->getLiquidsoapPayload($activeOutput);

            $outputSettings[] = $payload;
        }

        return $this->responseFactory->json([
            'outputs' => $outputSettings,
        ]);
    }

    /**
     * Sends chat message into available chat clients of active outputs
     * @param Request $request
     * @return Response
     */
    public function sendChatMessage(Request $request) : Response
    {
        $message = $request->post('message');
        Assert::string($message);
        Assert::notEmpty($message);

        foreach ($this->outputReadService->getAllActiveOutputs() as $activeOutput) {
            try {
                $driverName = $activeOutput->getDriverName();
                $driverClass = $this->driverRegistry->fetchDriverClassByName($driverName);

                if (!is_subclass_of($driverClass, ChatClientAwareInterface::class)) {
                    continue;
                }

                $chatClient = $this->createChatClient($driverClass::getChatClientClassName());
                $chatClient->sendMessage($activeOutput, $message);
            } catch (Throwable $throwable) {
                // skip failed output
                $this->logger->error(sprintf(
                    'Error while processing chat message for Output#%d : %s. Message: %s',
                    $activeOutput->getId(),
                    $throwable,
                    $message
                ));
            }
        }

        return $this->responseFactory->noContent();
    }

    /**
     * @param string $chatClientClassName
     * @return ChatClientInterface
     */
    private function createChatClient(string $chatClientClassName): ChatClientInterface
    {
        /** @var ChatClientInterface $chatClient */
        $chatClient = app($chatClientClassName);
        Assert::implementsInterface($chatClient, ChatClientInterface::class);

        return $chatClient;
    }
}

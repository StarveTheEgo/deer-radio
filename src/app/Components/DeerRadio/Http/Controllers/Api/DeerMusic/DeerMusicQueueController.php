<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\Liquidsoap\DeerMusic;

use App\Components\Attachment\Helper\AttachmentPathHelper;
use App\Components\DeerRadio\Metadata\SongMetadataBuilder;
use App\Components\DeerRadio\Service\CurrentSongUpdateService;
use App\Components\DeerRadio\Service\SongPickService;
use App\Components\DeerRadio\Service\SongQueueService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeerMusicQueueController extends Controller
{
    private ResponseFactory $responseFactory;

    private SongQueueService $songQueueService;

    private AttachmentPathHelper $attachmentPathHelper;

    private CurrentSongUpdateService $currentSongUpdateService;

    private SongMetadataBuilder $songMetadataBuilder;

    private SongPickService $songPickService;

    /**
     * @param ResponseFactory $responseFactory
     * @param SongQueueService $songQueueService
     * @param SongPickService $songPickService
     * @param CurrentSongUpdateService $currentSongUpdateService
     * @param SongMetadataBuilder $songMetadataBuilder
     * @param AttachmentPathHelper $attachmentPathHelper
     */
    public function __construct(
        ResponseFactory $responseFactory,
        SongQueueService $songQueueService,
        SongPickService $songPickService,
        CurrentSongUpdateService $currentSongUpdateService,
        SongMetadataBuilder $songMetadataBuilder,
        AttachmentPathHelper $attachmentPathHelper
    )
    {
        $this->responseFactory = $responseFactory;
        $this->songQueueService = $songQueueService;
        $this->songPickService = $songPickService;
        $this->currentSongUpdateService = $currentSongUpdateService;
        $this->songMetadataBuilder = $songMetadataBuilder;
        $this->attachmentPathHelper = $attachmentPathHelper;
    }

    /**
     * @return JsonResponse
     */
    public function enqueueNextSong() : JsonResponse
    {
        $nextSong = $this->songPickService->pickNextSong();
        if ($nextSong === null) {
            throw new HttpException(500, 'Did not pick any song');
        }

        $this->songQueueService->enqueueSong($nextSong);

        $songAttachment = $nextSong->getSongAttachment();
        if ($songAttachment === null) {
            throw new HttpException(500, sprintf(
                'Song #%d has no attachment',
                $nextSong->getId(),
            ));
        }

        return $this->responseFactory->json([
            'metadata' => $this->songMetadataBuilder->buildFromSong($nextSong),
            'path' => $this->attachmentPathHelper->getExistingPathOnDisk($songAttachment),
        ]);
    }

    /**
     * @param int $songId
     * @return Response
     */
    public function updateCurrentSongId(int $songId) : Response
    {
        $this->currentSongUpdateService->updateCurrentSongId($songId);
        return $this->responseFactory->noContent();
    }
}

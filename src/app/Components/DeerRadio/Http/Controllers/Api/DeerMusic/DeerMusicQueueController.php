<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Http\Controllers\Api\DeerMusic;

use App\Components\Attachment\Helper\AttachmentPathHelper;
use App\Components\DeerRadio\Enum\DeerRadioRoute;
use App\Components\DeerRadio\Metadata\SongMetadataBuilder;
use App\Components\DeerRadio\Service\CurrentSongUpdateService;
use App\Components\DeerRadio\Service\SongPickService;
use App\Components\DeerRadio\Service\SongQueueService;
use App\Components\Liquidsoap\AnnotationBuilder;
use App\Components\Song\Service\SongReadService;
use App\Components\Storage\Enum\StorageName;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator;
use JsonException;
use LogicException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Uid\UuidV4;

class DeerMusicQueueController extends Controller
{
    /**
     * @param ResponseFactory $responseFactory
     * @param SongQueueService $songQueueService
     * @param SongPickService $songPickService
     * @param SongReadService $songReadService
     * @param CurrentSongUpdateService $currentSongUpdateService
     * @param SongMetadataBuilder $songMetadataBuilder
     * @param AttachmentPathHelper $attachmentPathHelper
     * @param AnnotationBuilder $annotationBuilder
     * @param FilesystemManager $filesystemManager
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(private readonly ResponseFactory $responseFactory, private readonly SongQueueService $songQueueService, private readonly SongPickService $songPickService, private readonly SongReadService $songReadService, private readonly CurrentSongUpdateService $currentSongUpdateService, private readonly SongMetadataBuilder $songMetadataBuilder, private readonly AttachmentPathHelper $attachmentPathHelper, private readonly AnnotationBuilder $annotationBuilder, private readonly FilesystemManager $filesystemManager, private readonly UrlGenerator $urlGenerator)
    {
    }

    /**
     * @return JsonResponse
     * @throws JsonException
     */
    public function enqueueNextSong() : JsonResponse
    {
        $this->removeOldSongFiles();

        $nextSong = $this->songPickService->pickNextSong();
        if ($nextSong === null) {
            throw new HttpException(500, 'Did not pick any song');
        }

        // currently Liquidsoap does not support a lot of useful http features
        // so we have to pass the file directly to the storage
        // @todo implement a proper http client in liquidsoap (can be external, with Go / PHP / Python / whatever)
        $songStream = $this->getStreamedSongFile($nextSong->getId());
        $radioFs = $this->filesystemManager->disk(StorageName::RADIO_STORAGE->value);

        $tmpFileName = UuidV4::v4();
        $tmpFilePath = '/tmp-songs/'.$tmpFileName.'.bin';
        $isFileWritten = $radioFs->writeStream($tmpFilePath, $songStream);
        if (!$isFileWritten) {
            throw new LogicException("Could not write song file to $tmpFilePath");
        }

        $this->songQueueService->enqueueSong($nextSong);

        $songMetadata = $this->songMetadataBuilder->buildFromSong($nextSong);

        return $this->responseFactory->json([
            'annotatedPath' => $this->annotationBuilder->buildDataAnnotation(
                $radioFs->path($tmpFilePath),
                $songMetadata
            ),
            // note: is currently unused, but it would be a preferable way
            'downloadUrl' => $this->urlGenerator->route(DeerRadioRoute::DOWNLOAD_SONG->value, ['songId' => $nextSong->getId()]),
        ]);
    }

    /**
     * @param int $songId
     * @return StreamedResponse
     */
    public function downloadSong(int $songId): StreamedResponse
    {
        $songStream = $this->getStreamedSongFile($songId);

        return response()->streamDownload(function () use ($songStream) {
            set_time_limit(0);

            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            fpassthru($songStream);
        }, 'songfile');
    }

    /**
     * @param int $songId
     * @return resource
     */
    private function getStreamedSongFile(int $songId)
    {
        $song = $this->songReadService->getById($songId);
        $songAttachment = $song->getSongAttachment();

        if ($songAttachment === null) {
            throw new HttpException(500, sprintf(
                'Song #%d has no attachment',
                $song->getId(),
            ));
        }

        $songPath = $this->attachmentPathHelper->buildPathOnDisk($songAttachment);
        $songFs = $this->filesystemManager->disk($songAttachment->getDisk());

        $songStream = $songFs->readStream($songPath);
        if ($songStream === null) {
            throw new HttpException(500, sprintf(
                'Could not get the song stream for Song#%d (%s)',
                $song->getId(),
                $songPath
            ));
        }

        return $songStream;
    }

    private function removeOldSongFiles()
    {
        $radioFs = $this->filesystemManager->disk(StorageName::RADIO_STORAGE->value);
        $songsDirectoryPath = $radioFs->path('/tmp-songs/');

        $finder = new Finder();
        $finder
            ->files()
            ->in($songsDirectoryPath)
            ->depth('== 0')
            ->name('*.bin')
            ->sortByAccessedTime()
            ->reverseSorting();

        $i = 0;
        foreach ($finder as $file) {
            $i++;
            if ($i < 2) {
                continue;
            }

            $radioFs->delete('/tmp-songs/'.$file->getFilename());
        }
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

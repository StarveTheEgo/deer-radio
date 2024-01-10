<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\Attachment\Helper\AttachmentPathHelper;
use App\Components\Liquidsoap\AnnotationBuilder;
use App\Components\Song\Entity\Song;
use Illuminate\Filesystem\FilesystemManager;
use JsonException;
use LogicException;

class SongAnnotateService
{
    private FilesystemManager $filesystemManager;
    private AnnotationBuilder $annotationBuilder;

    private AttachmentPathHelper $attachmentPathHelper;

    public function __construct(
        FilesystemManager $filesystemManager,
        AnnotationBuilder $annotationBuilder,
        AttachmentPathHelper $attachmentPathHelper
    )
    {
        $this->filesystemManager = $filesystemManager;
        $this->annotationBuilder = $annotationBuilder;
        $this->attachmentPathHelper = $attachmentPathHelper;
    }

    /**
     * @param Song $song
     * @return string
     * @throws JsonException
     */
    public function annotate(Song $song) : string
    {
        $songId = $song->getId();
        $songAttachment = $song->getSongAttachment();
        if ($songAttachment === null) {
            throw new LogicException(sprintf(
                'Song #%d has no attachment',
                $songId
            ));
        }

        $disk = $this->filesystemManager->disk($songAttachment->getDisk());
        $songPath = $this->attachmentPathHelper->getPathOnDisk($songAttachment);

        if (!$disk->exists($songPath)) {
            throw new LogicException(sprintf(
                'Song #%d\'s attachment does not exist in: %s',
                $songId,
                $songPath
            ));
        }

        return $this->annotationBuilder->buildDataAnnotation(
            // @fixme security concern: API should not reveal full path
            $disk->path($songPath),
            $this->buildSongMetadata($song)
        );
    }

    /**
     * @param Song $song
     * @return scalar[]
     */
    private function buildSongMetadata(Song $song) : array
    {
        $album = $song->getAlbum();
        $author = $song->getAuthor();
        $label = $song->getLabel();
        $replayGain = number_format($song->getVolume() / 100, 2, '.', '');

        $authorLinks = [];
        foreach ($author->getLinks() as $authorLink) {
            $authorLink[] = $authorLink->getUrl();
        }

        $labelLinks = [];
        if ($label !== null) {
            foreach ($label->getLinks() as $labelLink) {
                $labelLinks[] = $labelLink->getUrl();
            }
        }

        return [
            'id' => $song->getId(),
            'artist' => $author->getName() ?? '<unknown>',
            'title' => $song->getTitle() ?? '<unknown>',
            'replay_gain' => $replayGain,
            'album' => $album->getTitle() ?? '',
            'album_year' => $album->getYear(),
            'author_links' => $authorLinks,
            'label' => $label?->getName() ?? '',
            'label_links' => $labelLinks,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Service;

use App\Components\Liquidsoap\AnnotationBuilder;
use App\Components\Song\Entity\Song;
use Illuminate\Filesystem\FilesystemManager;
use JsonException;
use LogicException;

class SongAnnotateService
{
    private FilesystemManager $filesystemManager;
    private AnnotationBuilder $annotationBuilder;

    public function __construct(
        FilesystemManager $filesystemManager,
        AnnotationBuilder $annotationBuilder,
    )
    {
        $this->filesystemManager = $filesystemManager;
        $this->annotationBuilder = $annotationBuilder;
    }

    /**
     * @param Song $song
     * @return string
     * @throws JsonException
     */
    public function annotate(Song $song) : string
    {
        $songAttachment = $song->getSongAttachment();
        if ($songAttachment === null) {
            throw new LogicException(sprintf(
                'Song #%d has no attachment',
                $song->getId()
            ));
        }

        $disk = $this->filesystemManager->disk($songAttachment->getDisk());
        $songFilePath = $disk->path($songAttachment->getPath());

        return $this->annotationBuilder->buildDataAnnotation(
            $songFilePath,
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
        foreach ($label->getLinks() as $labelLink) {
            $labelLinks[] = $labelLink->getUrl();
        }

        return [
            'id' => $song->getId(),
            'artist' => $author->getName() ?? '<unknown>',
            'title' => $song->getTitle() ?? '<unknown>',
            'replay_gain' => $replayGain,
            'album' => $album->getTitle() ?? '',
            'album_year' => $album->getYear(),
            'author_links' => $authorLinks,
            'label' => $label->getName() ?? '',
            'label_links' => $labelLinks,
        ];
    }
}

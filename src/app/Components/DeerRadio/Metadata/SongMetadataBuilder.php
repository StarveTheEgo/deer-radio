<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Metadata;

use App\Components\Song\Entity\Song;

class SongMetadataBuilder
{
    /**
     * @param Song $song
     * @return array<string, scalar|null>
     */
    public function buildFromSong(Song $song) : array
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
            'album' => $album?->getTitle() ?? '',
            'album_year' => $album?->getYear() ?? 'n/a',
            'author_links' => $authorLinks,
            'label' => $label?->getName() ?? '',
            'label_links' => $labelLinks,
        ];
    }
}

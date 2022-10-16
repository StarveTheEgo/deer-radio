<?php

namespace App\Observers;

use App\Models\Song;

class SongObserver
{
    public function deleting(Song $song)
    {
        $song->songAttachment()->delete();
    }
}

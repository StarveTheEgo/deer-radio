<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Commands;

use App\Components\DeerRadio\Service\CurrentSongUpdateService;
use Illuminate\Console\Command;

class UpdateNowPlayingId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'song:updateNowPlaying {song_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates now playing track';

    private CurrentSongUpdateService $currentSongUpdateService;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(CurrentSongUpdateService $currentSongUpdateService)
    {
        parent::__construct();
        $this->currentSongUpdateService = $currentSongUpdateService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $song_id = $this->argument('song_id');
        $this->currentSongUpdateService->updateCurrentSongId((int) $song_id);
    }
}

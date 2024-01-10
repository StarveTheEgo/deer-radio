<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Commands;

use App\Components\DeerRadio\Service\SongAnnotateService;
use App\Components\DeerRadio\Service\SongPickService;
use App\Components\DeerRadio\Service\SongQueueService;
use Exception;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class GetNextSong extends Command
{

    // use WithoutOverlapping;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'song:next {mode?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switches track to the next and returns its name with annotation';

    private SongPickService $songPickService;
    private SongQueueService $songQueueService;
    private SongAnnotateService $songAnnotateService;
    private LoggerInterface $logger;

    /**
     * Create a new command instance.
     *
     * @param SongPickService $songPickService
     * @param SongQueueService $songQueueService
     * @param SongAnnotateService $songAnnotateService
     * @param LoggerInterface $logger
     */
    public function __construct(
        SongPickService $songPickService,
        SongQueueService $songQueueService,
        SongAnnotateService $songAnnotateService,
        LoggerInterface $logger
    )
    {
        parent::__construct();

        $this->songPickService = $songPickService;
        $this->songQueueService = $songQueueService;
        $this->songAnnotateService = $songAnnotateService;
        $this->logger = $logger;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle() : void
    {
        // force queue even if queue is already not empty
        $forceQueue = $this->argument('mode') === 'force';

        if (!$forceQueue) {
            $queuedSong = $this->songQueueService->getQueuedSong();
            if ($queuedSong !== null) {
                $this->line($this->songAnnotateService->annotate($queuedSong));
                return;
            }
        }

        $nextSong = $this->songPickService->pickNextSong();
        if ($nextSong === null) {
            $this->logger->error('Did not pick any song');
            $this->line('');
            return;
        }

        $this->songQueueService->enqueueSong($nextSong);
        $this->line($this->songAnnotateService->annotate($nextSong));
    }
}

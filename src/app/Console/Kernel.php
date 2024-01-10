<?php

declare(strict_types=1);

namespace App\Console;

use App\Components\DeerRadio\Commands\DeerImageUpdate;
use App\Components\DeerRadio\Commands\GetCurrentDeerImage;
use App\Components\DeerRadio\Commands\GetNextSong;
use App\Components\DeerRadio\Commands\UpdateNowPlayingId;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var array<class-string<Command>>
     */
    protected $commands = [
        GetCurrentDeerImage::class,
        DeerImageUpdate::class,
        GetCurrentDeerImage::class,
        GetNextSong::class,
        UpdateNowPlayingId::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

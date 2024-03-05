<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\CreateLiquidsoapPersonalToken;
use App\Console\Commands\CreateLiquidsoapUser;
use App\Console\Commands\RefreshAccessTokens;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var array<class-string<Command>>
     */
    protected $commands = [
        RefreshAccessTokens::class,
        CreateLiquidsoapUser::class,
        CreateLiquidsoapPersonalToken::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // OAuth2 access tokens management
        $schedule->command('access-token:refresh')->everyMinute();

        // Sanctum personal access tokens management
        $schedule->command('liquidsoap:personal-token')->hourly();
        $schedule->command('sanctum:prune-expired --hours=2')->everyOddHour();

        // Liquidsoap diagnosis
        $schedule->command('liquidsoap:keep-alive')->everyTwoMinutes();
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

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('bot:run')->everyMinute();

        $schedule->command('bot:one')->everyMinute();
        $schedule->command('bot:five')->everyFiveMinutes();
        $schedule->command('bot:ten')->everyTenMinutes();
        $schedule->command('bot:fiveteen')->everyFifteenMinutes();
        $schedule->command('bot:thirty')->everyThirtyMinutes();
        $schedule->command('bot:sixty')->hourly();

        $schedule->command('price:update')->everyFifteenMinutes();
        $schedule->command('auto:cancel')->everyMinute();
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

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
        $schedule->command('price:update')->cron('13-59/15 * * * *');
        $schedule->command('bot:run')->everyMinute();

        $schedule->command('bot:one')->everyMinute();
        $schedule->command('bot:five')->everyFiveMinutes();
        $schedule->command('bot:ten')->everyTenMinutes();
        $schedule->command('bot:fiveteen')->everyFifteenMinutes();
        $schedule->command('bot:thirty')->everyThirtyMinutes();
        $schedule->command('bot:sixty')->hourly();


        $schedule->command('auto:cancel')->cron('2-59/15 * * * *');
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

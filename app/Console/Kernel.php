<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('schedule:daily')->daily();
        $schedule->command('schedule:weekly')->weekly();
        $schedule->command('schedule:tenDays')->cron('0 0 1-10 * *');
        $schedule->command('schedule:monthly')->monthly();
        $schedule->command('schedule:yearly')->yearly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

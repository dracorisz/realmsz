<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->call(function () {
        //     Artisan::call('down', [
        //         '--message' => 'The application is under maintenance. Please check back later.',
        //         '--retry' => 60,
        //     ]);
        // })->dailyAt('02:00'); // Example: Enable maintenance mode daily at 2 AM
        
        // Sync content with dracoscopia.com every hour
        $schedule->command('content:sync')->hourly();
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

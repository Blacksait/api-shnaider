<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Laravelista\LumenVendorPublish\VendorPublishCommand::class,
        \App\Console\Commands\GetUsers::class,
        \App\Console\Commands\GetSessionList::class,
        \App\Console\Commands\GetSpeakers::class,
        \App\Console\Commands\GetSessionPersonalList::class,
        \App\Console\Commands\DischargeMeetings::class,
        \App\Console\Commands\GenerateStat::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('get:users')->everyFiveMinutes();
//        $schedule->command('get:sessionlist')->dailyAt('17:00');
//        $schedule->command('get:usersessionlist')->dailyAt('17:00');
//        $schedule->command('get:speakers')->dailyAt('17:00');
        $schedule->command('discharge:meetings')->dailyAt('19:00');
    }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\UpdateHolidays::class,
    ];

    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('holidays:update')->yearly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
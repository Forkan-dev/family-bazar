<?php

namespace App\Console;


use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ClearOldCartHistory::class,
    ];

    // protected function schedule(Schedule $schedule): void
    // {
    //     $schedule->job(new ClearOldCartHistoryJob())->hourly();

    // }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

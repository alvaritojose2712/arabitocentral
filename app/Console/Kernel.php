<?php

namespace App\Console;

use App\Http\Controllers\NominaController;
use App\Http\Controllers\CierresGeneralController;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        return $schedule->call(function () {
            
            $fecha = (new NominaController)->today();
            (new CierresGeneralController)->sendReporteFun($fecha,"enviar","");
        })
        ->dailyAt('23:30');
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

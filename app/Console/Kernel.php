<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // app/Console/Kernel.php

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tahun-ajaran:generate')
                 ->yearly()
                 ->onJuly(1); // Eksekusi setiap 1 Juli
    }

}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // app/Console/Kernel.php

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tahun-ajaran:generate')->yearlyOn(1, '00:00');
    }

}

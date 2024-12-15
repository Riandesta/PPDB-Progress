<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\GenerateTahunAjaran;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar perintah Artisan kustom aplikasi.
     *
     * @var array
     */
    protected $commands = [
        GenerateTahunAjaran::class
    ];

    /**
     * Tentukan jadwal perintah Artisan.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Menjadwalkan perintah untuk dijalankan setiap tahun pada 1 Januari pukul 00:00
        $schedule->command('tahun-ajaran:generate')->yearlyOn(1, 1, '00:00');
    }

    /**
     * Daftar perintah Artisan yang tersedia melalui aplikasi konsol.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

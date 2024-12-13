<?php

namespace App\Console\Commands;

use App\Models\TahunAjaran;
use Illuminate\Console\Command;

class GenerateTahunAjaran extends Command
{
    protected $signature = 'tahun-ajaran:generate';
    protected $description = 'Generate tahun ajaran baru secara otomatis';

    public function handle()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        // Cek apakah tahun ajaran sudah ada
        $exists = TahunAjaran::where('tahun_ajaran', "$currentYear/$nextYear")->exists();

        if (!$exists) {
            TahunAjaran::create([
                'tahun_ajaran' => "$currentYear/$nextYear",
                'is_active' => false,
                'tanggal_mulai' => "$currentYear-07-01",
                'tanggal_selesai' => "$nextYear-06-30",
            ]);

            $this->info("Tahun ajaran $currentYear/$nextYear berhasil dibuat");
        } else {
            $this->info("Tahun ajaran $currentYear/$nextYear sudah ada");
        }
    }
}

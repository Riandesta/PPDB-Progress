<?php

namespace App\Console\Commands;

use App\Models\TahunAjaran;
use Illuminate\Console\Command;

class GenerateTahunAjaran extends Command
{
    protected $signature = 'tahun-ajaran:generate';
    protected $description = 'Generate tahun ajaran baru dan nonaktifkan tahun ajaran lama';

    // app/Console/Commands/GenerateTahunAjaran.php
public function handle()
{
    $currentYear = date('Y');
    $nextYear = $currentYear + 1;

    // Nonaktifkan semua tahun ajaran yang ada
    TahunAjaran::where('is_active', true)->update(['is_active' => false]);

    // Buat tahun ajaran baru
    TahunAjaran::create([
        'tahun_ajaran' => "$currentYear/$nextYear",
        'tahun_mulai' => $currentYear,
        'tahun_selesai' => $nextYear,
        'is_active' => true,
        'tanggal_mulai' => "$currentYear-07-01",
        'tanggal_selesai' => "$nextYear-06-30",
    ]);

    $this->info("Tahun ajaran $currentYear/$nextYear berhasil dibuat");
}

}

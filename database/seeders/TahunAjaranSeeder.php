<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   // TahunAjaranSeeder.php
public function run(): void
{
    $this->command->info('Creating active Tahun Ajaran for 2024/2025...');
    
    TahunAjaran::create([
        'tahun_ajaran' => '2024/2025',
        'tahun_mulai' => 2024,
        'tahun_selesai' => 2025,
        'is_active' => true,
        'tanggal_mulai' => '2024-07-01',
        'tanggal_selesai' => '2025-06-30',
    ]);
}

}

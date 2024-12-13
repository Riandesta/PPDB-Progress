<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Non-aktifkan semua tahun ajaran yang ada
        $this->command->info('Disabling all existing Tahun Ajaran...');
        TahunAjaran::query()->update(['is_active' => false]);

        // Buat tahun ajaran baru yang aktif
        $this->command->info('Creating active Tahun Ajaran for 2024/2025...');
        TahunAjaran::create([
            'tahun_ajaran' => '2024/2025',
            'is_active' => true,
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
        ]);

        $this->command->info('Creating non-active Tahun Ajaran for 2023/2024...');
        // Buat tahun ajaran tidak aktif
        TahunAjaran::create([
            'tahun_ajaran' => '2023/2024',
            'is_active' => false,
            'tanggal_mulai' => '2023-07-01',
            'tanggal_selesai' => '2024-06-30',
        ]);

        $this->command->info('Tahun Ajaran seeding completed successfully!');
    }
}

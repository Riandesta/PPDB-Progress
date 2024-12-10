<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Jurusan;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data di tabel jurusans sebelum menjalankan seeder ini.
        $jurusan = Jurusan::first(); // Mengambil jurusan pertama sebagai contoh

        if (!$jurusan) {
            $this->command->info('No jurusan found. Please seed jurusans table first.');
            return;
        }

        // Menambahkan beberapa kelas untuk jurusan
        for ($i = 1; $i <= 3; $i++) {
            Kelas::create([
                'jurusan_id' => $jurusan->id,
                'nama_kelas' => 'Kelas ' . chr(64 + $i), // Kelas A, B, C
                'tahun_ajaran' => '2024/2025',
                'urutan_kelas' => $i,
                'kapasitas_saat_ini' => rand(10, 20), // Dummy kapasitas
            ]);
        }

        $this->command->info('Kelas seeded successfully.');
    }
}

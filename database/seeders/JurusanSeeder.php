<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::create([
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            'kode_jurusan' => 'RPL',
            'deskripsi' => 'Jurusan RPL',
            'kapasitas_per_kelas' => 15,
            'max_kelas' => 3
        ]);
    }
}

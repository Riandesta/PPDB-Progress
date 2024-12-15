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
        $jurusans = [
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'kode_jurusan' => 'RPL',
                'deskripsi' => 'Jurusan RPL',
                'kapasitas_per_kelas' => 15,
                'max_kelas' => 3
            ],
            [
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'kode_jurusan' => 'TKJ', 
                'deskripsi' => 'Jurusan TKJ',
                'kapasitas_per_kelas' => 15,
                'max_kelas' => 3
            ],
            [
                'nama_jurusan' => 'Teknik Permesinan',
                'kode_jurusan' => 'TP',
                'deskripsi' => 'Jurusan TP',
                'kapasitas_per_kelas' => 15,
                'max_kelas' => 3
            ],
            [
                'nama_jurusan' => 'Teknik Kendaraan Ringan Otomotif',
                'kode_jurusan' => 'TKRO',
                'deskripsi' => 'Jurusan TKRO',
                'kapasitas_per_kelas' => 15,
                'max_kelas' => 3
            ],
            [
                'nama_jurusan' => 'Teknik Bisnis Sepeda Motor',
                'kode_jurusan' => 'TBSM',
                'deskripsi' => 'Jurusan TBSM',
                'kapasitas_per_kelas' => 15,
                'max_kelas' => 3
            ]
        ];
        
        foreach ($jurusans as $jurusan) {
            Jurusan::create($jurusan);
        }
    }
}

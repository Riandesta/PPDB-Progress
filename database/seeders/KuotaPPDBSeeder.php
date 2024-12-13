<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\KuotaPPDB;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KuotaPPDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        $jurusans = Jurusan::all();

        foreach ($jurusans as $jurusan) {
            KuotaPPDB::create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jurusan_id' => $jurusan->id,
                'kuota' => $jurusan->kapasitas_per_kelas * $jurusan->max_kelas
            ]);
        }
    }
}

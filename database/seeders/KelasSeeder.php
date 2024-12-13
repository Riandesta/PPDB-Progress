<?php
namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        $jurusans = Jurusan::all();

        foreach ($jurusans as $jurusan) {
            for ($i = 1; $i <= $jurusan->max_kelas; $i++) {
                Kelas::create([
                    'jurusan_id' => $jurusan->id,
                    'nama_kelas' => $jurusan->kode_jurusan . ' ' . chr(64 + $i), // A, B, C, dst
                    'tahun_ajaran' => $tahunAjaran->tahun_ajaran,
                    'urutan_kelas' => $i,
                    'kapasitas_saat_ini' => 0
                ]);
            }
        }
    }
}

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
        // Ambil tahun ajaran aktif
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        
        if (!$tahunAjaran) {
            $this->command->error('Tahun Ajaran aktif tidak ditemukan!');
            return;
        }

        // Ambil semua jurusan
        $jurusans = Jurusan::all();
        
        if ($jurusans->isEmpty()) {
            $this->command->error('Data jurusan tidak ditemukan!');
            return;
        }

        // Buat kelas untuk setiap jurusan
        foreach ($jurusans as $jurusan) {
            // Buat kelas sesuai max_kelas dari jurusan
            for ($i = 1; $i <= $jurusan->max_kelas; $i++) {
                Kelas::create([
                    'jurusan_id' => $jurusan->id,
                    'nama_kelas' => $jurusan->kode_jurusan . ' ' . chr(64 + $i), // Contoh: RPL A, RPL B, dst
                    'tahun_ajaran' => $tahunAjaran->tahun_ajaran,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'urutan_kelas' => $i,
                    'kapasitas_saat_ini' => 0
                ]);
            }
            
            $this->command->info("Kelas untuk jurusan {$jurusan->nama_jurusan} berhasil dibuat!");
        }
        
        $this->command->info('Semua kelas berhasil dibuat!');
    }
}

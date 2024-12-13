<?php
// database/seeders/PembayaranPPDBSeeder.php

namespace Database\Seeders;

use App\Models\Administrasi;
use App\Models\PembayaranPPDB;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class PembayaranPPDBSeeder extends Seeder
{
    public function run()
    {
        // Dapatkan tahun ajaran yang aktif
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        if (!$tahunAjaran) {
            $this->command->error('Tahun Ajaran aktif tidak ditemukan. Jalankan TahunAjaranSeeder terlebih dahulu.');
            return;
        }

        // Buat data pembayaran untuk tahun ajaran aktif
        Administrasi::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'biaya_pendaftaran' => 100000,
            'biaya_ppdb' => 5000000,
            'biaya_mpls' => 250000,
            'biaya_awal_tahun' => 1500000
        ]);

        // Buat juga data pembayaran untuk tahun ajaran non-aktif
        $tahunAjaranNonAktif = TahunAjaran::where('is_active', false)->get();

        foreach ($tahunAjaranNonAktif as $tahun) {
            Administrasi::create([
                'tahun_ajaran_id' => $tahun->id,
                'biaya_pendaftaran' => 100000,
                'biaya_ppdb' => 5000000,
                'biaya_mpls' => 250000,
                'biaya_awal_tahun' => 1500000
            ]);
        }

        $this->command->info('Data pembayaran PPDB berhasil dibuat.');
    }
}

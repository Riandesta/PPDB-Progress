<?php

namespace Database\Seeders;

use App\Models\CalonSiswa;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class CalonSiswaSeeder extends Seeder
{
    public function run()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaran) {
            $this->command->error('Tahun Ajaran aktif tidak ditemukan!');
            return;
        }

        $jurusan = Jurusan::first();
        if (!$jurusan) {
            $this->command->error('Jurusan tidak ditemukan!');
            return;
        }

        $kelas = Kelas::first();
        if (!$kelas) {
            $this->command->error('Kelas tidak ditemukan!');
            return;
        }

        for ($i = 1; $i <= 40; $i++) {
            $siswas[] = [
                'NISN' => '202401' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama' => 'Siswa RPL ' . $i,
                'alamat' => 'Alamat Siswa ' . $i,
                'tgl_lahir' => '2006-03-16',
                'tmp_lahir' => 'Jakarta',
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'agama' => 'Buddha',
                'asal_sekolah' => 'SMP ' . (6 + $i % 3),
                'nama_ortu' => 'Orang Tua ' . $i,
                'pekerjaan_ortu' => $i % 2 == 0 ? 'Wiraswasta' : 'PNS',
                'no_telp_ortu' => '08559930' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tahun_ajaran' => $tahunAjaran->tahun_ajaran,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jurusan_id' => $jurusan->id,
                'status_dokumen' => true,
                'nilai_semester_1' => rand(60, 100),
                'nilai_semester_2' => rand(60, 100),
                'nilai_semester_3' => rand(60, 100),
                'nilai_semester_4' => rand(60, 100),
                'nilai_semester_5' => rand(60, 100),
                'rata_rata_nilai' => rand(70, 90),
                'status_seleksi' => $i % 2 == 0 ? 'Lulus' : 'Tidak Lulus',
                'kelas_id' => $kelas->id,
            ];
        }

        foreach ($siswas as $siswa) {
            $calonSiswa = Pendaftaran::create($siswa);

            // Generate random payment status
            $isPendaftaranLunas = rand(0, 1);
            $isPPDBLunas = rand(0, 1);
            $isMPLSLunas = rand(0, 1);
            $isAwalTahunLunas = rand(0, 1);

            $biayaPendaftaran = 100000;
            $biayaPPDB = 5000000;
            $biayaMPLS = 250000;
            $biayaAwalTahun = 1500000;

            $totalBayar = 0;
            if ($isPendaftaranLunas) $totalBayar += $biayaPendaftaran;
            if ($isPPDBLunas) $totalBayar += $biayaPPDB;
            if ($isMPLSLunas) $totalBayar += $biayaMPLS;
            if ($isAwalTahunLunas) $totalBayar += $biayaAwalTahun;

            $statusPembayaran = $totalBayar >= ($biayaPendaftaran + $biayaPPDB + $biayaMPLS + $biayaAwalTahun) ? 'Lunas' : 'Belum Lunas';

            \App\Models\Administrasi::create([
                'pendaftaran_id' => $calonSiswa->id,
                'biaya_pendaftaran' => $biayaPendaftaran,
                'biaya_ppdb' => $biayaPPDB,
                'biaya_mpls' => $biayaMPLS,
                'biaya_awal_tahun' => $biayaAwalTahun,
                'total_bayar' => $totalBayar,
                'status_pembayaran' => $statusPembayaran,
                'is_pendaftaran_lunas' => $isPendaftaranLunas,
                'is_ppdb_lunas' => $isPPDBLunas,
                'is_mpls_lunas' => $isMPLSLunas,
                'is_awal_tahun_lunas' => $isAwalTahunLunas,
                'tanggal_bayar_pendaftaran' => $isPendaftaranLunas ? now() : null,
                'tanggal_bayar_ppdb' => $isPPDBLunas ? now() : null,
                'tanggal_bayar_mpls' => $isMPLSLunas ? now() : null,
                'tanggal_bayar_awal_tahun' => $isAwalTahunLunas ? now() : null,
            ]);
        }

        $this->command->info('Data 40 calon siswa dengan status pembayaran bervariasi berhasil dibuat!');
    }
}

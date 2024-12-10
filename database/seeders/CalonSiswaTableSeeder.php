<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonSiswa;
use App\Models\Jurusan;
use App\Services\KelasService;

class CalonSiswaTableSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada jurusan
        $jurusan = Jurusan::first();
        if (!$jurusan) {
            $this->command->error('Jurusan not found. Please run JurusanSeeder first.');
            return;
        }

        // Data siswa dengan nama yang terdistribusi berdasarkan abjad
        $siswaData = [
            // A-F
            ['nama' => 'Ahmad Rizki', 'NISN' => '1234567801'],
            ['nama' => 'Budi Santoso', 'NISN' => '1234567802'],
            ['nama' => 'Citra Dewi', 'NISN' => '1234567803'],
            ['nama' => 'Dian Pratama', 'NISN' => '1234567804'],
            ['nama' => 'Eko Prasetyo', 'NISN' => '1234567805'],
            ['nama' => 'Fajar Hidayat', 'NISN' => '1234567806'],

            // G-L
            ['nama' => 'Gunawan', 'NISN' => '1234567807'],
            ['nama' => 'Hadi Kusuma', 'NISN' => '1234567808'],
            ['nama' => 'Indra Wijaya', 'NISN' => '1234567809'],
            ['nama' => 'Joko Widodo', 'NISN' => '1234567810'],
            ['nama' => 'Kartika Sari', 'NISN' => '1234567811'],
            ['nama' => 'Linda Maharani', 'NISN' => '1234567812'],

            // M-R
            ['nama' => 'Muhammad Ali', 'NISN' => '1234567813'],
            ['nama' => 'Nina Safitri', 'NISN' => '1234567814'],
            ['nama' => 'Oscar Pranata', 'NISN' => '1234567815'],
            ['nama' => 'Putri Handayani', 'NISN' => '1234567816'],
            ['nama' => 'Queen Rahmawati', 'NISN' => '1234567817'],
            ['nama' => 'Rudi Hermawan', 'NISN' => '1234567818'],

            // S-Z
            ['nama' => 'Siti Nurhaliza', 'NISN' => '1234567819'],
            ['nama' => 'Tono Sucipto', 'NISN' => '1234567820'],
            ['nama' => 'Udin Sedunia', 'NISN' => '1234567821'],
            ['nama' => 'Vina Panduwinata', 'NISN' => '1234567822'],
            ['nama' => 'Wati Sulistyo', 'NISN' => '1234567823'],
            ['nama' => 'Yanto Pribadi', 'NISN' => '1234567824'],
            ['nama' => 'Zainab Putri', 'NISN' => '1234567825'],

            // Tambahan untuk melengkapi 45 siswa
            ['nama' => 'Adi Putra', 'NISN' => '1234567826'],
            ['nama' => 'Bakti Rahman', 'NISN' => '1234567827'],
            ['nama' => 'Cindy Ayu', 'NISN' => '1234567828'],
            ['nama' => 'Dewi Sari', 'NISN' => '1234567829'],
            ['nama' => 'Erlangga Wijaya', 'NISN' => '1234567830'],
            ['nama' => 'Farhan Malik', 'NISN' => '1234567831'],
            ['nama' => 'Gita Kurnia', 'NISN' => '1234567832'],
            ['nama' => 'Hari Setiawan', 'NISN' => '1234567833'],
            ['nama' => 'Intan Permata', 'NISN' => '1234567834'],
            ['nama' => 'Januar Firmansyah', 'NISN' => '1234567835'],
            ['nama' => 'Kirana Ayu', 'NISN' => '1234567836'],
            ['nama' => 'Lutfi Pranata', 'NISN' => '1234567837'],
            ['nama' => 'Mia Andini', 'NISN' => '1234567838'],
            ['nama' => 'Naufal Hadi', 'NISN' => '1234567839'],
            ['nama' => 'Oka Pratama', 'NISN' => '1234567840'],
            ['nama' => 'Pandu Wicaksono', 'NISN' => '1234567841'],
            ['nama' => 'Quincy Dwi', 'NISN' => '1234567842'],
            ['nama' => 'Rani Kusuma', 'NISN' => '1234567843'],
            ['nama' => 'Satria Wirawan', 'NISN' => '1234567844'],
            ['nama' => 'Tasya Nuraini', 'NISN' => '1234567845']
        ];


        $kelasService = app(KelasService::class);

        foreach ($siswaData as $data) {
            $siswa = CalonSiswa::create([
                'NISN' => $data['NISN'],
                'nama' => $data['nama'],
                'alamat' => 'Jl. Contoh No. ' . rand(1, 100),
                'tgl_lahir' => '2006-' . rand(1, 12) . '-' . rand(1, 28),
                'tmp_lahir' => 'Kota ' . ['Jakarta', 'Bandung', 'Surabaya'][rand(0, 2)],
                'jenis_kelamin' => ['L', 'P'][rand(0, 1)],
                'agama' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'][rand(0, 4)],
                'asal_sekolah' => 'SMP Negeri ' . rand(1, 10) . ' Jakarta',
                'nama_ortu' => 'Orang Tua ' . $data['nama'],
                'pekerjaan_ortu' => ['PNS', 'Wiraswasta', 'Pegawai Swasta'][rand(0, 2)],
                'no_telp_ortu' => '08' . rand(100000000, 999999999),
                'tahun_ajaran' => '2024/2025',
                'jurusan_id' => $jurusan->id,
                'status_dokumen' => true,
                'nilai_semester_1' => rand(75, 100),
                'nilai_semester_2' => rand(75, 100),
                'nilai_semester_3' => rand(75, 100),
                'nilai_semester_4' => rand(75, 100),
                'nilai_semester_5' => rand(75, 100),
                'status_seleksi' => 'Lulus'
            ]);

            // Assign ke kelas
            $kelasService->assignSiswaToCalonSiswa($siswa);
        }
    }
}

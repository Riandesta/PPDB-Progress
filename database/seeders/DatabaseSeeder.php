<?php
namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\KelasSeeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\JurusanSeeder;
use Database\Seeders\KuotaPPDBSeeder;
use Database\Seeders\TahunAjaranSeeder;
use Database\Seeders\PembayaranPPDBSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Cek apakah sudah ada tahun ajaran aktif
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        // 2. Jika belum ada, generate tahun ajaran baru
        if (!$tahunAjaran) {
            $this->call(TahunAjaranSeeder::class);
        }

        $this->call([
            RoleSeeder::class,
            UsersSeeder::class,
            JurusanSeeder::class,      // 1. Jurusan harus dibuat terlebih dahulu
            KuotaPPDBSeeder::class,    // 2. Kuota membutuhkan Jurusan
            KelasSeeder::class,        // 3. Kelas harus dibuat sebelum CalonSiswa
            CalonSiswaSeeder::class,   // 4. Terakhir baru CalonSiswa
            PembayaranPPDBSeeder::class
        ]);
        
        
        
        

    }
}

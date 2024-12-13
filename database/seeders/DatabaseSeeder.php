<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CalonSiswaTableSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,        // Jalankan RoleSeeder terlebih dahulu
            UsersSeeder::class,        // Kemudian UserSeeder
            TahunAjaranSeeder::class, // Buat tahun ajaran
            JurusanSeeder::class,     // Buat jurusan
            PembayaranPPDBSeeder::class, // Set biaya PPDB
            KuotaPPDBSeeder::class,   // Set kuota per jurusan
            KelasSeeder::class,       // Buat kelas default
            CalonSiswaSeeder::class,  // Buat data siswa testing
        ]);
    }
}

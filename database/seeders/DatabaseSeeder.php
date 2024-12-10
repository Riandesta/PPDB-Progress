<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            JurusanSeeder::class,  // Pastikan jurusan dibuat dulu
            CalonSiswaTableSeeder::class,
            // KelasSeeder::class
            
        ]);
    }
}

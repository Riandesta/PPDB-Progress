<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin PPDB',
                'email' => 'admin@p',
                'password' => Hash::make('admin@p'),
                // 'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Panitia PPDB',
                'email' => 'panitia@ppdb.com',
                'password' => Hash::make('panitia123'),
                // 'role' => 'panitia',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

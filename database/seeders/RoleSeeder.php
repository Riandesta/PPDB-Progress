<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin'
            ],
            [
                'name' => 'Panitia PPDB',
                'slug' => 'panitia'
            ],
            [
                'name' => 'Keuangan',
                'slug' => 'keuangan'
            ],
            [
                'name' => 'Calon Siswa',
                'slug' => 'siswa'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

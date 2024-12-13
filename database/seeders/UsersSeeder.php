<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Buat user admin default
            $admin = User::create([
                'name' => 'Admin PPDB',
                'email' => 'admin@ppdb.test',
                'password' => Hash::make('password'),
            ]);
    
            // Attach role admin
            $adminRole = Role::where('slug', 'admin')->first();
            if ($adminRole) {
                $admin->roles()->attach($adminRole->id);
            }
    
            // Buat user panitia default
            $panitia = User::create([
                'name' => 'Panitia PPDB',
                'email' => 'panitia@ppdb.test',
                'password' => Hash::make('password'),
            ]);
    
            // Attach role panitia
            $panitiaRole = Role::where('slug', 'panitia')->first();
            if ($panitiaRole) {
                $panitia->roles()->attach($panitiaRole->id);
            }
    
            $this->command->info('Data user default berhasil dibuat!');
        }
    }

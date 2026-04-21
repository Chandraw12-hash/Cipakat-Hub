<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Cipakat',
            'email' => 'admin@cipakat.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas BUMDes',
            'email' => 'petugas@cipakat.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Warga Desa',
            'email' => 'warga@cipakat.com',
            'password' => Hash::make('warga123'),
            'role' => 'warga',
        ]);
    }
}

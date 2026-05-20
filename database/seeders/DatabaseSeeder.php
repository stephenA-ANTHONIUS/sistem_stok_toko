<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun Admin default
        User::firstOrCreate(
            ['email' => 'admin@toko.com'],
            [
                'name'     => 'Admin Toko',
                'email'    => 'admin@toko.com',
                'role'     => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // Buat akun Pemilik default
        User::firstOrCreate(
            ['email' => 'pemilik@toko.com'],
            [
                'name'     => 'Pemilik Toko',
                'email'    => 'pemilik@toko.com',
                'role'     => 'pemilik',
                'password' => Hash::make('password'),
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrCreate agar tidak error duplikat jika dijalankan 2x
        User::updateOrCreate(
            ['email' => 'admin@assetsync.com'], // Email login
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // Password login
                'email_verified_at' => now(),
            ]
        );
    }
}
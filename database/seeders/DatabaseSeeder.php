<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin default
        User::firstOrCreate(
            ['email' => 'admin@nammerce.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Pelanggan contoh
        User::firstOrCreate(
            ['email' => 'pelanggan@nammerce.com'],
            [
                'name' => 'Pelanggan',
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
            ]
        );
    }
}

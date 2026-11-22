<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database with dummy users for each role.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@toko.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Staff Barang',
            'email' => 'staff@toko.com',
            'password' => bcrypt('password'),
            'role' => 'staff_barang',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@toko.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);
    }
}

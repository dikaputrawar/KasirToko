<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default users for the application
        User::updateOrCreate(
            ['email' => 'admin@warungapp.local'],
            [
                'name' => 'Admin',
                'password' => 'password', // will be hashed by model cast
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@warungapp.local'],
            [
                'name' => 'Kasir',
                'password' => 'password', // will be hashed by model cast
                'role' => 'kasir',
            ]
        );
    }
}

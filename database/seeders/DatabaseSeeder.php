<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // This is the corrected, idempotent way to seed a specific user.
        User::updateOrCreate(
            [
                'email' => 'ranaelectronics@gmail.com' // Attribute to find the user by
            ],
            [
                'name'     => 'Rana Electronics',
                'role'     => 'admin',
                'password' => Hash::make('admin@123'),
            ]
        );
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Customer
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@gmail.com',
            'password' => '123',
            'role' => 'customer',
        ]);
    }
}

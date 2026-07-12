<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'image' => 'admin.png',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@gmail.com'],
            [
                'name' => 'Cashier One',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'image' => 'cashier.png',
            ]
        );
    }
}
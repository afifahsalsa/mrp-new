<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Super User',
                'email' => 'superuser@gmail.com',
                'password' => Hash::make('87654321'),
                'pw' => '87654321',
                'user_code' => 'SU',
                'role' => 'superuser'
            ],
            [
                'name' => 'Lilla Dea Pratiwi',
                'email' => 'Lilla@gmail.com',
                'password' => Hash::make('12345678'),
                'pw' => '12345678',
                'user_code' => 'LDP',
                'role' => 'staff'
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'passwrod' => Hash::make('111111'),
                'pw' => '111111',
                'user_code' => 'ADM',
                'role' => 'admin'
            ]
        ]);
    }
}

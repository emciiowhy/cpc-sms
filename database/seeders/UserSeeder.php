<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User',     'email' => 'admin@school.com',     'role' => 'admin'],
            ['name' => 'Registrar User', 'email' => 'registrar@school.com', 'role' => 'registrar'],
            ['name' => 'Guidance User',  'email' => 'guidance@school.com',  'role' => 'guidance'],
            ['name' => 'Clinic User',    'email' => 'clinic@school.com',    'role' => 'clinic'],
            ['name' => 'SAO User',       'email' => 'sao@school.com',       'role' => 'sao'],
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'role'     => $user['role'],
                'password' => Hash::make('password123'),
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@school.com',
                'password' => bcrypt('password123'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Registrar User',
                'email' => 'registrar@school.com',
                'password' => bcrypt('password123'),
                'role' => 'Registrar',
            ],
            [
                'name' => 'Guidance User',
                'email' => 'guidance@school.com',
                'password' => bcrypt('password123'),
                'role' => 'Guidance',
            ],
            [
                'name' => 'Clinic User',
                'email' => 'clinic@school.com',
                'password' => bcrypt('password123'),
                'role' => 'Clinic',
            ],
            [
                'name' => 'SAO User',
                'email' => 'sao@school.com',
                'password' => bcrypt('password123'),
                'role' => 'SAO',
            ],
        ];
    
        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
    
}
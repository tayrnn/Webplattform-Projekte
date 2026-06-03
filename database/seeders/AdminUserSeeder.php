<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test Student',
            'username' => 'student1',
            'email' => 'student@test.local',
            'password' => Hash::make('student12345'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Test Lehrer',
            'username' => 'lehrer1',
            'email' => 'lehrer@test.local',
            'password' => Hash::make('lehrer12345'),
            'role' => 'lehrende',
        ]);
    }
}
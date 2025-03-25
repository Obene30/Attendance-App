<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role; // ✅ THIS IS REQUIRED

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Shepherd']);
        Role::firstOrCreate(['name' => 'Member']);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@msciarmley.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('Admin');

        // Shepherds
        $shepherd1 = User::firstOrCreate(
            ['email' => 'emeka@msciarmley.com'],
            [
                'first_name' => 'Emeka',
                'last_name' => 'User',
                'password' => bcrypt('password'),
            ]
        );
        $shepherd1->assignRole('Shepherd');

        $shepherd2 = User::firstOrCreate(
            ['email' => 'kim@msciarmley.com'],
            [
                'first_name' => 'Kim',
                'last_name' => 'User',
                'password' => bcrypt('password'),
            ]
        );
        $shepherd2->assignRole('Shepherd');

        // Member
        $member = User::firstOrCreate(
            ['email' => 'israel@msciarmley.com'],
            [
                'first_name' => 'Israel',
                'last_name' => 'User',
                'password' => bcrypt('password'),
            ]
        );
        $member->assignRole('Member');
    }
}

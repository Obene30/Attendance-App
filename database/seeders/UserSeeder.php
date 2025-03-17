<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@msciarmley.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');
    
        // Create Pastor User
        $shepherd = User::create([
            'first_name' => 'shepherd',
            'last_name' => 'User',
            'email' => 'shepherd@msciarmley.com',
            'password' => bcrypt('password'),
        ]);
        $shepherd->assignRole('shepherd');
    
        // Create Member User
        $member = User::create([
            'first_name' => 'Member',
            'last_name' => 'User',
            'email' => 'Israel@msciarmley.com',
            'password' => bcrypt('password'),
        ]);
        $member->assignRole('Member');
    }
}

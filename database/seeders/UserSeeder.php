<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Shepherd']);
        Role::firstOrCreate(['name' => 'Member']);

        // 🔹 Admins with unique passwords
        $admins = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@msciarmley.com',
                'password' => 'password'
            ],
            [
                'first_name' => 'Rev Emil',
                'last_name' => 'T',
                'email' => 'emil@msciarmley.com',
                'password' => 'password'
            ],



            [
                'first_name' => 'Ayo',
                'last_name' => 'Agbaje',
                'email' => 'ayo@msciarmley.com',
                'password' => 'password'
            ],
        ];
        

        foreach ($admins as $adminData) {
            $admin = User::firstOrCreate(
                ['email' => $adminData['email']],
                [
                    'first_name' => $adminData['first_name'],
                    'last_name' => $adminData['last_name'],
                    'password' => bcrypt($adminData['password']),
                ]
            );
            $admin->assignRole('Admin');
        }

        // 🔹 Shepherds with unique passwords
        $shepherds = [
            [
                'first_name' => 'Emeka',
                'last_name' => 'User',
                'email' => 'emeka@msciarmley.com',
                'password' => 'password'
            ],
            [
                'first_name' => 'Kim',
                'last_name' => 'User',
                'email' => 'kim@msciarmley.com',
                'password' => 'password'
            ],
            [
                'first_name' => 'Goodluck',
                'last_name' => 'User',
                'email' => 'goodluck@msciarmley.com',
                'password' => 'lpassword'
            ],
        ];

        foreach ($shepherds as $data) {
            $shepherd = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => bcrypt($data['password']),
                ]
            );
            $shepherd->assignRole('Shepherd');
        }

        // 🔹 Member (optional: use same or random password)
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

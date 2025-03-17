<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage users',
            'manage attendees',
            'view reports',
            'mark attendance',
            'export pdf',
            'view attendance',
            'export excel'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $shepherd = Role::firstOrCreate(['name' => 'Shepherd']);
        $member = Role::firstOrCreate(['name' => 'Member']);

        // Assign permissions to roles
        $admin->givePermissionTo(['manage users', 'manage attendees', 'view reports', 'mark attendance', 'export pdf']);
        $shepherd->givePermissionTo(['manage attendees', 'view attendance', 'mark attendance']);
        $member->givePermissionTo(['mark attendance']);
    }
}


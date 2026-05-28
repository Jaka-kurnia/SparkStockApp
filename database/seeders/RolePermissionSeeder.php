<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage-suppliers',
            'manage-spareparts',
            'manage-customers',
            'manage-vehicles',
            'manage-mechanic',
            'manage-services',
            'manage-permissions',
            'manage-stock-transaction'
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Create Roles and Assign Permissions
        $ownerRole = Role::findOrCreate('owner', 'web');
        $ownerRole->syncPermissions($permissions); // Owner has all permissions

        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions([
            'manage-suppliers',
            'manage-spareparts',
            'manage-customers',
            'manage-vehicles',
            'manage-services',
            'manage-mechanic',
        ]); // Admin has all except manage-permissions

        $kasirRole = Role::findOrCreate('kasir', 'web');
        $kasirRole->syncPermissions([
            'manage-customers',
            'manage-services',
        ]); // Kasir can manage customers & services

        $mekanikRole = Role::findOrCreate('mekanik', 'web');
        $mekanikRole->syncPermissions([
            'manage-vehicles',
            'manage-services',
        ]); // Mekanik can manage vehicles & services

        // Create Default Users and Assign Roles
        $users = [
            [
                'name' => 'Owner SparkStock',
                'email' => 'owner@sparkstock.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ],
            [
                'name' => 'Admin SparkStock',
                'email' => 'admin@sparkstock.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Kasir SparkStock',
                'email' => 'kasir@sparkstock.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ],
            [
                'name' => 'Mekanik SparkStock',
                'email' => 'mekanik@sparkstock.com',
                'password' => Hash::make('password'),
                'role' => 'mekanik',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            // Assign role
            $user->syncRoles([$userData['role']]);
        }
    }
}

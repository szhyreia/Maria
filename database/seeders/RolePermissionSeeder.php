<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions with api guard
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role permissions
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Content permissions (example)
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'api'],
                ['guard_name' => 'api']
            );
        }

        // Create roles with api guard
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'api'],
            ['guard_name' => 'api']
        );
        
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api'],
            ['guard_name' => 'api']
        );
        
        $editorRole = Role::firstOrCreate(
            ['name' => 'editor', 'guard_name' => 'api'],
            ['guard_name' => 'api']
        );
        
        $userRole = Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'api'],
            ['guard_name' => 'api']
        );

        // Assign permissions to roles
        // Admin gets all permissions except delete
        $adminRole->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'view roles',
            'view posts',
            'create posts',
            'edit posts',
            'publish posts',
        ]);

        // Editor gets content permissions
        $editorRole->syncPermissions([
            'view posts',
            'create posts',
            'edit posts',
        ]);

        // Regular user gets view only
        $userRole->syncPermissions([
            'view posts',
        ]);

        // Create default users with roles
        $this->createDefaultUsers();
    }

    private function createDefaultUsers()
    {
        // First, ensure all roles exist
        $roles = [
            'super-admin' => Role::findByName('super-admin', 'api'),
            'admin' => Role::findByName('admin', 'api'),
            'editor' => Role::findByName('editor', 'api'),
            'user' => Role::findByName('user', 'api'),
        ];

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole($roles['super-admin']);
        }

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($roles['admin']);
        }

        // Create Editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$editor->hasRole('editor')) {
            $editor->assignRole($roles['editor']);
        }

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$user->hasRole('user')) {
            $user->assignRole($roles['user']);
        }

        $this->command->info('Default users created successfully!');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'student']);

        // Create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage courses']);
        Permission::create(['name' => 'view grades']);
        Permission::create(['name' => 'edit grades']);
        Permission::create(['name' => 'view own grades']);
        Permission::create(['name' => 'submit assignments']);

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        $teacherRole = Role::findByName('teacher');
        $teacherRole->givePermissionTo(['manage courses', 'view grades', 'edit grades']);

        $studentRole = Role::findByName('student');
        $studentRole->givePermissionTo(['view own grades', 'submit assignments']);
    }
}


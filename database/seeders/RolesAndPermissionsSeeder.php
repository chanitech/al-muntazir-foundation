<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    

public function run()
{
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions (match the ones used in adminlte menu)
    $permissions = [
        'view members', 'create members', 'edit members', 'delete members',
        'view academic institutions', 'create academic institutions', 'edit academic institutions', 'delete academic institutions',
        'view membership types', 'create membership types', 'edit membership types', 'delete membership types',
        'view events', 'create events', 'edit events', 'delete events',
        'view event categories', 'create event categories', 'edit event categories', 'delete event categories',
        'view documents', 'create documents', 'edit documents', 'delete documents',
        'view document categories', 'create document categories', 'edit document categories', 'delete document categories',
        'view elections', 'create elections', 'edit elections', 'delete elections',
        'view candidates', 'create candidates', 'edit candidates', 'delete candidates',
        'view positions', 'create positions', 'edit positions', 'delete positions',
        'vote', 'view voting analytics',
        'change password',
        'view roles', 'create roles', 'edit roles', 'delete roles',
        'manage website',
    ];

    foreach ($permissions as $perm) {
        Permission::firstOrCreate(['name' => $perm]);
    }

    // Create roles
    $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
    $superAdmin->givePermissionTo(Permission::all());

    $admin = Role::firstOrCreate(['name' => 'Admin']);
    $admin->givePermissionTo([
        'view members', 'create members', 'edit members',
        'view events', 'create events', 'edit events',
        'view documents', 'create documents', 'edit documents',
        'view elections', 'create elections', 'edit elections',
        'view candidates', 'create candidates', 'edit candidates',
        'view positions', 'create positions', 'edit positions',
        'vote', 'view voting analytics',
        'change password',
        'manage website',
    ]);

    // Can only edit the public website (sliders, news, gallery, settings...)
    $websiteEditor = Role::firstOrCreate(['name' => 'Website Editor']);
    $websiteEditor->givePermissionTo(['manage website', 'change password']);

    $member = Role::firstOrCreate(['name' => 'Member']);
    $member->givePermissionTo([
        'view members',
        'view events',
        'view documents',
        'vote',
        'change password',
    ]);
}
}

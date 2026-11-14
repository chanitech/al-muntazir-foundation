<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates the "manage website" permission and grants it to the existing
 * Super Admin and Admin roles, plus a new "Website Editor" role for people
 * who should only edit website content. Running `php artisan migrate` is
 * enough for the Website menu to appear in the admin panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permission = Permission::findOrCreate('manage website', 'web');

        foreach (['Super Admin', 'Admin'] as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            $role?->givePermissionTo($permission);
        }

        $editor = Role::findOrCreate('Website Editor', 'web');
        $editor->givePermissionTo($permission);

        if ($changePassword = Permission::where('name', 'change password')->where('guard_name', 'web')->first()) {
            $editor->givePermissionTo($changePassword);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::where('name', 'Website Editor')->where('guard_name', 'web')->delete();
        Permission::where('name', 'manage website')->where('guard_name', 'web')->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};

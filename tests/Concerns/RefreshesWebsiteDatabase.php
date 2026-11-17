<?php

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Fresh database for each test (all migrations) plus helpers for the
 * Website admin tests.
 */
trait RefreshesWebsiteDatabase
{
    use RefreshDatabase;

    /** A user who can manage the website (the "Website Editor" role). */
    protected function websiteEditor(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('Website Editor', 'web'));

        return $user;
    }
}

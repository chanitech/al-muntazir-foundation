<?php

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Migrates only the tables the public website and its admin section use.
 *
 * Some older migrations of the membership module are dated before the tables
 * they alter, so a full `migrate:fresh` fails on an empty database. These
 * tests therefore run the relevant migrations explicitly, in order.
 */
trait RefreshesWebsiteDatabase
{
    use RefreshDatabase;

    protected function migrateFreshUsing(): array
    {
        $files = [
            '0001_01_01_000000_create_users_table.php',
            '0001_01_01_000001_create_cache_table.php',
            '0001_01_01_000002_create_jobs_table.php',
            '2026_02_21_193941_create_permission_tables.php',
            '2026_02_23_153438_create_event_categories_table.php',
            '2026_02_23_153438_create_events_table.php',
            '2026_02_23_162906_add_sms_fields_to_events_table.php',
            '2026_02_23_210501_add_deleted_at_to_events_table.php',
            '2026_02_23_210807_add_soft_deletes_to_event_categories_table.php',
            '2026_02_23_223857_add_sms_template_to_events.php',
            '2026_11_14_100000_create_website_content_tables.php',
            '2026_11_14_100001_create_or_update_sliders_table.php',
            '2026_11_14_100002_add_website_fields_to_events_table.php',
            '2026_11_14_100003_create_website_permissions.php',
        ];

        return [
            '--drop-views' => false,
            '--drop-types' => false,
            '--path' => array_map(fn ($file) => database_path('migrations/'.$file), $files),
            '--realpath' => true,
        ];
    }

    /** A user who can manage the website (the "Website Editor" role). */
    protected function websiteEditor(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('Website Editor', 'web'));

        return $user;
    }
}

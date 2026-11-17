<?php

namespace Tests\Concerns;

use App\Models\MaritalStatus;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

/**
 * Fresh database with the real roles and permissions (Super Admin, Admin,
 * Member, Website Editor), plus helpers to create users and members.
 */
trait SeedsMembership
{
    use RefreshDatabase;

    /** Called automatically by Laravel after the database is refreshed. */
    protected function setUpSeedsMembership(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    protected function membershipType(string $name = 'Ordinary'): MembershipType
    {
        return MembershipType::firstOrCreate(['name' => $name], ['is_active' => true]);
    }

    protected function maritalStatus(string $name): MaritalStatus
    {
        return MaritalStatus::firstOrCreate(['name' => $name]);
    }

    /** A member, optionally with a login account. */
    protected function member(array $attributes = [], ?User $user = null): Member
    {
        static $sequence = 0;
        $sequence++;

        return Member::create(array_merge([
            'user_id' => $user?->id,
            'membership_number' => 'AYFC-'.str_pad((string) (900000 + $sequence), 6, '0', STR_PAD_LEFT),
            'join_date' => now(),
            'membership_type_id' => $this->membershipType()->id,
            'status' => 'Active',
            'first_name' => 'Test',
            'last_name' => 'Member '.$sequence,
            'full_name' => 'Test Member '.$sequence,
            'phone' => '0700'.Str::padLeft((string) $sequence, 6, '0'),
            'children_count' => 0,
        ], $attributes));
    }

    /** A logged-in member account with the "Member" role. */
    protected function votingMember(): array
    {
        $user = $this->userWithRole('Member');

        return [$user, $this->member([], $user)];
    }
}

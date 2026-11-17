<?php

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\SeedsMembership;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    use SeedsMembership;

    private function wizardData(array $overrides = []): array
    {
        return array_merge([
            'membership_type_id' => $this->membershipType()->id,
            'status' => 'Active',
            'role' => 'Member',
            'first_name' => 'Hassan',
            'middle_name' => 'Ali',
            'last_name' => 'Mussa',
            'date_of_birth' => '2000-01-15',
            'phone' => '0712000111',
            'email' => 'hassan@example.com',
            'current_country' => 'Tanzania',
            'current_region' => 'Arusha',
            'current_district' => 'Arusha City',
            'current_village' => 'Kaloleni',
            'is_employed' => '1',
            'employer_name' => 'Tanzania Ports',
            'job_title' => 'Clerk',
            'employment_status' => 'full time',
        ], $overrides);
    }

    public function test_the_registration_wizard_saves_all_member_details(): void
    {
        $this->actingAs($this->userWithRole('Admin'))
            ->post('/admin/members/wizard/store', $this->wizardData())
            ->assertRedirect(route('admin.members.index'));

        $member = Member::sole();
        // These fields used to be silently dropped.
        $this->assertSame('Hassan', $member->first_name);
        $this->assertSame('Mussa', $member->last_name);
        $this->assertSame('Arusha', $member->current_region);
        $this->assertSame('Kaloleni', $member->current_village);
        $this->assertTrue($member->is_employed);
        $this->assertSame('Tanzania Ports', $member->employer_name);
        $this->assertSame('Hassan Ali Mussa', $member->full_name);
        $this->assertSame('AYFC-000001', $member->membership_number);

        $this->assertTrue($member->user->hasRole('Member'));

        // A random password, shown once; no longer guessable from the phone number.
        $this->assertSame('hassan@example.com', session('new_login.email'));
        $this->assertTrue(Hash::check(session('new_login.password'), $member->user->password));
        $this->assertFalse(Hash::check('000111@Member', $member->user->password));

        // The region and employment filters on the members list now work.
        $this->get('/admin/members?region=Arusha&employed=1')->assertSee('Hassan Ali Mussa');
        $this->get('/admin/members?region=Mwanza')->assertDontSee('Hassan Ali Mussa');
    }

    public function test_single_members_do_not_need_spouse_details_but_married_members_do(): void
    {
        $this->actingAs($this->userWithRole('Admin'));

        $this->post('/admin/members/wizard/store', $this->wizardData(['marital_status_id' => $this->maritalStatus('Single')->id]))
            ->assertSessionHasNoErrors();

        $this->post('/admin/members/wizard/store', $this->wizardData([
            'phone' => '0712000222', 'email' => 'second@example.com',
            'marital_status_id' => $this->maritalStatus('Married')->id,
        ]))->assertSessionHasErrors(['spouse_first_name', 'spouse_surname', 'spouse_phone']);

        $this->assertSame(1, Member::count());
    }

    public function test_wizard_validation_errors_are_shown_and_input_is_kept(): void
    {
        $this->member(['phone' => '0712000111']);

        $this->actingAs($this->userWithRole('Admin'))
            ->from('/admin/members/create')
            ->post('/admin/members/wizard/store', $this->wizardData())
            ->assertRedirect('/admin/members/create')
            ->assertSessionHasErrors('phone')
            ->assertSessionHasInput('first_name', 'Hassan');

        $this->followRedirects($this->get('/admin/members/create'))
            ->assertSee('The phone has already been taken', false)
            ->assertSee('value="Hassan"', false);
    }

    public function test_membership_numbers_are_never_reused_after_a_member_is_deleted(): void
    {
        $this->actingAs($this->userWithRole('Super Admin'));
        $this->post('/admin/members/wizard/store', $this->wizardData());
        $first = Member::sole();
        $this->delete("/admin/members/{$first->id}")->assertRedirect();

        $this->post('/admin/members/wizard/store', $this->wizardData(['phone' => '0712000999', 'email' => 'new@example.com']))
            ->assertSessionHasNoErrors();

        $this->assertSame('AYFC-000002', Member::sole()->membership_number);
    }

    public function test_changing_a_members_role_is_saved(): void
    {
        $user = $this->userWithRole('Member');
        $member = $this->member([], $user);

        $this->actingAs($this->userWithRole('Super Admin'))
            ->put("/admin/members/{$member->id}", [
                'membership_type_id' => $member->membership_type_id,
                'status' => 'Active',
                'first_name' => 'Test', 'last_name' => 'Member',
                'phone' => $member->phone,
                'role' => 'Admin',
            ])->assertRedirect(route('admin.members.index'));

        $this->assertTrue($user->fresh()->hasRole('Admin'));
        $this->assertFalse($user->fresh()->hasRole('Member'));
    }

    public function test_ordinary_members_cannot_change_or_delete_other_members(): void
    {
        $other = $this->member();
        $this->actingAs($this->userWithRole('Member'));

        $this->get("/admin/members/{$other->id}/edit")->assertForbidden();
        $this->put("/admin/members/{$other->id}", ['first_name' => 'Hacked'])->assertForbidden();
        $this->delete("/admin/members/{$other->id}")->assertForbidden();
        $this->get('/admin/members/create')->assertForbidden();

        $this->assertNotNull($other->fresh());
        // Viewing the directory is still allowed for members.
        $this->get('/admin/members')->assertOk();
    }

    public function test_admins_can_edit_but_only_super_admins_can_delete_members(): void
    {
        $member = $this->member();

        $this->actingAs($this->userWithRole('Admin'))->delete("/admin/members/{$member->id}")->assertForbidden();
        $this->actingAs($this->userWithRole('Super Admin'))->delete("/admin/members/{$member->id}")->assertRedirect();

        $this->assertSoftDeleted($member);
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin/members')->assertRedirect(route('login'));
        $this->get('/documents')->assertRedirect(route('login'));
        $this->get('/admin/voting')->assertRedirect(route('login'));
    }

    public function test_members_can_be_exported_to_csv(): void
    {
        $this->member(['full_name' => 'Export Person']);

        $response = $this->actingAs($this->userWithRole('Admin'))->get('/admin/members/export/csv');

        $response->assertOk();
        $this->assertStringContainsString('Export Person', $response->streamedContent() ?: file_get_contents($response->baseResponse->getFile()->getPathname()));
    }

    public function test_the_change_password_page_works(): void
    {
        $user = User::factory()->create(['password' => bcrypt('old-password')]);
        $user->assignRole('Member');

        $this->actingAs($user)
            ->from('/password/change')
            ->post('/password/change', [
                'current_password' => 'old-password',
                'password' => 'New-password-2026',
                'password_confirmation' => 'New-password-2026',
            ])
            ->assertRedirect('/password/change')
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('New-password-2026', $user->fresh()->password));
    }
}

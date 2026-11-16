<?php

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\SeedsMembership;
use Tests\TestCase;

class MembershipApplicationTest extends TestCase
{
    use SeedsMembership;

    private function validApplication(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Amina',
            'middle_name' => 'Said',
            'last_name' => 'Juma',
            'date_of_birth' => '2004-05-10',
            'phone' => '+255 712 345 678',
            'email' => 'amina@example.com',
            'current_region' => 'Dar es Salaam',
            'current_district' => 'Ilala',
            'education' => 'Form Six',
            'occupation' => 'Student',
            'motivation' => 'I want to serve my community.',
            'consent' => '1',
        ], $overrides);
    }

    public function test_the_application_form_is_public_and_lists_regions(): void
    {
        Region::create(['name' => 'Arusha']);

        $this->get('/membership/apply')
            ->assertOk()
            ->assertSee('Become a Member')
            ->assertSee('Arusha');

        // "Join Us" on the website leads to the form.
        $this->get('/')->assertSee(route('site.membership.apply'), false);
    }

    public function test_a_visitor_can_apply_and_gets_a_reference_number(): void
    {
        $response = $this->post('/membership/apply', $this->validApplication())->assertRedirect('/membership/apply');

        $application = MembershipApplication::sole();
        $response->assertSessionHas('application_sent', $application->reference());
        $this->assertMatchesRegularExpression('/^APP-\d{6}$/', $application->reference());
        $this->assertSame('pending', $application->status);
        $this->assertSame('Amina Said Juma', $application->fullName());
        $this->assertSame('Ilala', $application->current_district);

        $this->followingRedirects()
            ->post('/membership/apply', $this->validApplication(['phone' => '0755 000 111']))
            ->assertSee(MembershipApplication::latest('id')->first()->reference());
    }

    public function test_the_application_is_validated(): void
    {
        $this->from('/membership/apply')
            ->post('/membership/apply', $this->validApplication(['first_name' => '', 'phone' => 'call me', 'consent' => null]))
            ->assertRedirect('/membership/apply')
            ->assertSessionHasErrors(['first_name', 'phone', 'consent']);

        $this->assertSame(0, MembershipApplication::count());
    }

    public function test_spam_and_duplicate_pending_applications_are_not_stored_twice(): void
    {
        $this->post('/membership/apply', $this->validApplication(['website' => 'http://spam.example']));
        $this->assertSame(0, MembershipApplication::count());

        $this->post('/membership/apply', $this->validApplication());
        $this->post('/membership/apply', $this->validApplication());
        $this->assertSame(1, MembershipApplication::count());
    }

    public function test_an_admin_can_approve_an_application_which_creates_the_member_and_login(): void
    {
        $type = $this->membershipType();
        $application = MembershipApplication::create($this->validApplication(['status' => 'pending']));
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->get('/admin/applications')->assertOk()->assertSee('Amina Said Juma');
        $this->get("/admin/applications/{$application->id}")->assertOk()->assertSee('Approve and create member');

        $response = $this->post("/admin/applications/{$application->id}/approve", [
            'membership_type_id' => $type->id,
            'create_account' => '1',
            'role' => 'Member',
        ]);

        $member = Member::sole();
        $response->assertRedirect(route('admin.applications.show', $application))
            ->assertSessionHas('success')
            ->assertSessionHas('new_login.email', 'amina@example.com');

        $this->assertSame('Amina Said Juma', $member->full_name);
        $this->assertSame('Amina', $member->first_name);
        $this->assertSame('Dar es Salaam', $member->current_region);
        $this->assertSame('+255 712 345 678', $member->phone);
        $this->assertMatchesRegularExpression('/^AYFC-\d{6}$/', $member->membership_number);
        $this->assertSame('Form Six', $member->academic_history[0]['education_level']);

        $user = User::where('email', 'amina@example.com')->sole();
        $this->assertTrue($user->hasRole('Member'));
        $this->assertSame($user->id, $member->user_id);
        $this->assertTrue(Hash::check(session('new_login.password'), $user->password));

        $application->refresh();
        $this->assertSame('approved', $application->status);
        $this->assertSame($member->id, $application->member_id);
        $this->assertSame($admin->id, $application->reviewed_by);

        // Already reviewed: cannot be approved again.
        $this->post("/admin/applications/{$application->id}/approve", ['membership_type_id' => $type->id])->assertStatus(409);
    }

    public function test_approval_without_a_login_account(): void
    {
        $type = $this->membershipType();
        $application = MembershipApplication::create($this->validApplication(['status' => 'pending', 'email' => null]));

        $this->actingAs($this->userWithRole('Admin'))
            ->post("/admin/applications/{$application->id}/approve", ['membership_type_id' => $type->id, 'create_account' => '0'])
            ->assertSessionMissing('new_login');

        $this->assertNull(Member::sole()->user_id);
        $this->assertSame(1, User::count()); // only the admin
    }

    public function test_an_application_from_an_existing_member_cannot_be_approved(): void
    {
        $this->member(['phone' => '+255 712 345 678']);
        $application = MembershipApplication::create($this->validApplication(['status' => 'pending']));
        $this->actingAs($this->userWithRole('Admin'));

        $this->get("/admin/applications/{$application->id}")->assertSee('already exists');
        $this->post("/admin/applications/{$application->id}/approve", ['membership_type_id' => $this->membershipType()->id])
            ->assertSessionHasErrors('phone');

        $this->assertSame(1, Member::count());
    }

    public function test_an_admin_can_reject_an_application(): void
    {
        $application = MembershipApplication::create($this->validApplication(['status' => 'pending']));

        $this->actingAs($this->userWithRole('Admin'))
            ->post("/admin/applications/{$application->id}/reject", ['review_note' => 'Incomplete details'])
            ->assertRedirect(route('admin.applications.index'));

        $this->assertSame('rejected', $application->fresh()->status);
        $this->assertSame('Incomplete details', $application->fresh()->review_note);
        $this->assertSame(0, Member::count());
    }

    public function test_ordinary_members_cannot_review_applications(): void
    {
        $application = MembershipApplication::create($this->validApplication(['status' => 'pending']));

        $this->actingAs($this->userWithRole('Member'))
            ->post("/admin/applications/{$application->id}/approve", ['membership_type_id' => $this->membershipType()->id])
            ->assertForbidden();

        $this->assertSame('pending', $application->fresh()->status);
    }

    public function test_the_dashboard_and_menu_show_waiting_applications(): void
    {
        MembershipApplication::create($this->validApplication(['status' => 'pending']));

        $this->actingAs($this->userWithRole('Super Admin'))
            ->get('/admin/applications')
            ->assertSee('Membership Applications')
            ->assertSee('badge-warning', false);
    }
}

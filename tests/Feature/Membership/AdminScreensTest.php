<?php

namespace Tests\Feature\Membership;

use App\Models\AcademicInstitution;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Election;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\MembershipApplication;
use App\Models\Position;
use Spatie\Permission\Models\Role;
use Tests\Concerns\SeedsMembership;
use Tests\TestCase;

/** Opens every page of the membership system to catch broken screens. */
class AdminScreensTest extends TestCase
{
    use SeedsMembership;

    public function test_every_membership_admin_page_opens_for_a_super_admin(): void
    {
        $admin = $this->userWithRole('Super Admin');
        $member = $this->member(['current_region' => 'Arusha', 'is_employed' => true, 'employment_status' => 'full time'], $admin);
        $this->maritalStatus('Married');
        $type = $this->membershipType();
        $institution = AcademicInstitution::create(['name' => 'University of Dar es Salaam', 'is_active' => true]);
        $eventCategory = EventCategory::create(['name' => 'Meetings', 'is_active' => true]);
        $event = Event::create(['event_category_id' => $eventCategory->id, 'title' => 'AGM', 'event_date' => now()->addWeek()->toDateString(), 'status' => 'upcoming']);
        $documentCategory = DocumentCategory::create(['name' => 'Reports', 'slug' => 'reports', 'is_active' => true]);
        $document = Document::create(['document_category_id' => $documentCategory->id, 'title' => 'Annual report', 'file_path' => 'documents/report.pdf', 'file_name' => 'report.pdf', 'file_extension' => 'pdf', 'file_size' => 1000]);
        $election = Election::create(['name' => 'Leadership 2027', 'start_time' => now()->subDay(), 'end_time' => now()->addDay(), 'is_active' => true]);
        $position = Position::create(['name' => 'Chairperson']);
        Candidate::create(['member_id' => $member->id, 'election_id' => $election->id, 'position_id' => $position->id, 'is_approved' => true]);
        $application = MembershipApplication::create(['first_name' => 'Asha', 'last_name' => 'Ali', 'phone' => '0711000000', 'status' => 'pending']);
        $role = Role::findByName('Member', 'web');

        $pages = [
            '/dashboard',
            '/admin/members', '/admin/members?region=Arusha&employed=1', '/admin/members/create',
            "/admin/members/{$member->id}/edit", "/admin/members/{$member->id}/profile",
            '/admin/applications', '/admin/applications?status=all', "/admin/applications/{$application->id}",
            '/membership-types', '/membership-types/create', "/membership-types/{$type->id}/edit",
            '/academic-institutions', '/academic-institutions/create', "/academic-institutions/{$institution->id}/edit",
            '/academic', '/academic/create',
            '/admin/events', '/admin/events/create', "/admin/events/{$event->id}/edit",
            '/admin/event-categories', '/admin/event-categories/create', "/admin/event-categories/{$eventCategory->id}/edit",
            '/documents', '/documents/create', "/documents/{$document->id}",
            '/document-categories', '/document-categories/create', "/document-categories/{$documentCategory->id}/edit",
            '/admin/elections', '/admin/elections/create', "/admin/elections/{$election->id}/edit",
            '/admin/candidates', '/admin/candidates/create',
            '/admin/positions', '/admin/positions/create', "/admin/positions/{$position->id}/edit",
            '/admin/voting', "/admin/voting/election/{$election->id}", "/admin/voting/results/{$election->id}",
            '/admin/voting/analytics', "/admin/voting/analytics?election_id={$election->id}",
            '/admin/roles', '/admin/roles/create', "/admin/roles/{$role->id}/edit",
            '/password/change',
        ];

        $this->actingAs($admin);
        $failures = [];

        foreach ($pages as $page) {
            $response = $this->get($page);
            if ($response->status() !== 200) {
                $failures[] = $page.' → HTTP '.$response->status();
            }
        }

        $this->assertSame([], $failures, "These pages did not open:\n".implode("\n", $failures));
    }
}

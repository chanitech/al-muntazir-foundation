<?php

namespace Tests\Feature\Membership;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\SeedsMembership;
use Tests\TestCase;

class DocumentsAndEventsTest extends TestCase
{
    use SeedsMembership;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');
    }

    private function category(): DocumentCategory
    {
        return DocumentCategory::firstOrCreate(['slug' => 'reports'], ['name' => 'Reports', 'is_active' => true]);
    }

    public function test_uploaded_documents_are_stored_privately_and_downloaded_through_the_app(): void
    {
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->post('/documents', [
            'document_category_id' => $this->category()->id,
            'title' => 'Constitution',
            'file' => UploadedFile::fake()->create('constitution.pdf', 120, 'application/pdf'),
        ])->assertRedirect(route('documents.index'));

        $document = Document::sole();
        Storage::disk('local')->assertExists($document->file_path);
        // Not in the public folder that is reachable at /storage/...
        Storage::disk('public')->assertMissing($document->file_path);

        // Members can download it through the permission-checked link.
        $this->actingAs($this->userWithRole('Member'))
            ->get(route('documents.secure-download', $document))
            ->assertOk()
            ->assertDownload('constitution.pdf');

        $this->assertSame(1, $document->fresh()->download_count);
    }

    public function test_documents_uploaded_by_older_versions_still_download(): void
    {
        Storage::disk('public')->put('documents/old.pdf', 'old file');
        $document = Document::create([
            'document_category_id' => $this->category()->id, 'title' => 'Old minutes',
            'file_path' => 'documents/old.pdf', 'file_name' => 'old.pdf', 'file_extension' => 'pdf', 'file_size' => 8,
        ]);

        $this->actingAs($this->userWithRole('Member'))
            ->get(route('documents.secure-download', $document))
            ->assertOk()
            ->assertDownload('old.pdf');
    }

    public function test_the_documents_secure_command_moves_old_public_files_to_private_storage(): void
    {
        Storage::disk('public')->put('documents/old.pdf', 'old file');
        Document::create([
            'document_category_id' => $this->category()->id, 'title' => 'Old minutes',
            'file_path' => 'documents/old.pdf', 'file_name' => 'old.pdf', 'file_extension' => 'pdf', 'file_size' => 8,
        ]);

        $this->artisan('documents:secure --dry-run')->expectsOutputToContain('1 file(s) would be moved')->assertSuccessful();
        Storage::disk('public')->assertExists('documents/old.pdf');

        $this->artisan('documents:secure')->expectsOutputToContain('1 file(s) moved')->assertSuccessful();
        Storage::disk('public')->assertMissing('documents/old.pdf');
        $this->assertSame('old file', Storage::disk('local')->get('documents/old.pdf'));
    }

    public function test_members_can_read_documents_but_not_upload_or_delete_them(): void
    {
        $document = Document::create([
            'document_category_id' => $this->category()->id, 'title' => 'Budget',
            'file_path' => 'documents/budget.pdf', 'file_name' => 'budget.pdf', 'file_extension' => 'pdf', 'file_size' => 8,
        ]);
        $this->actingAs($this->userWithRole('Member'));

        $this->get('/documents')->assertOk()->assertSee('Budget');
        $this->get('/documents/create')->assertForbidden();
        $this->delete("/documents/{$document->id}")->assertForbidden();
        $this->assertNotNull($document->fresh());
    }

    public function test_deleting_a_document_removes_its_file(): void
    {
        Storage::disk('local')->put('documents/x.pdf', 'x');
        $document = Document::create([
            'document_category_id' => $this->category()->id, 'title' => 'X',
            'file_path' => 'documents/x.pdf', 'file_name' => 'x.pdf', 'file_extension' => 'pdf', 'file_size' => 1,
        ]);

        $this->actingAs($this->userWithRole('Super Admin'))->delete("/documents/{$document->id}")->assertRedirect();

        Storage::disk('local')->assertMissing('documents/x.pdf');
        $this->assertNull($document->fresh());
    }

    public function test_duplicate_document_category_names_show_a_message_instead_of_an_error(): void
    {
        $this->category();

        $this->actingAs($this->userWithRole('Super Admin'))
            ->post('/document-categories', ['name' => 'Reports'])
            ->assertSessionHasErrors('name');
    }

    public function test_events_can_be_saved_as_drafts(): void
    {
        $category = EventCategory::create(['name' => 'Meetings', 'is_active' => true]);

        $this->actingAs($this->userWithRole('Admin'))->post('/admin/events', [
            'event_category_id' => $category->id,
            'title' => 'Planning meeting',
            'event_date' => now()->addWeek()->toDateString(),
            'status' => 'draft',
        ])->assertRedirect(route('events.index'));

        $this->assertSame('draft', Event::sole()->status);
    }

    public function test_only_active_event_categories_are_offered(): void
    {
        EventCategory::create(['name' => 'Active Category', 'is_active' => true]);
        EventCategory::create(['name' => 'Retired Category', 'is_active' => false]);

        $this->actingAs($this->userWithRole('Admin'))
            ->get('/admin/events/create')
            ->assertSee('Active Category')
            ->assertDontSee('Retired Category');
    }

    public function test_members_cannot_create_change_or_delete_events(): void
    {
        $category = EventCategory::create(['name' => 'Meetings', 'is_active' => true]);
        $event = Event::create(['event_category_id' => $category->id, 'title' => 'AGM', 'event_date' => now()->toDateString(), 'status' => 'upcoming']);
        $this->actingAs($this->userWithRole('Member'));

        $this->get('/admin/events')->assertOk();
        $this->get('/admin/events/create')->assertForbidden();
        $this->put("/admin/events/{$event->id}", ['title' => 'Changed'])->assertForbidden();
        $this->delete("/admin/events/{$event->id}")->assertForbidden();

        $this->assertSame('AGM', $event->fresh()->title);
    }
}

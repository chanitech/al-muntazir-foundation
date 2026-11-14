<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EventStatusTest extends TestCase
{
    /**
     * A full migrate:fresh fails in this repo (an "add fields to members"
     * migration runs before the members table exists), so only the
     * migrations the events screens need are run, including the one that
     * turns events.status into a string.
     */
    private const MIGRATIONS = [
        '0001_01_01_000000_create_users_table.php',
        '2026_02_21_193941_create_permission_tables.php',
        '2026_02_23_153438_create_event_categories_table.php',
        '2026_02_23_153438_create_events_table.php',
        '2026_02_23_162906_add_sms_fields_to_events_table.php',
        '2026_02_23_210501_add_deleted_at_to_events_table.php',
        '2026_02_23_210807_add_soft_deletes_to_event_categories_table.php',
        '2026_02_23_223857_add_sms_template_to_events.php',
        '2026_11_14_000000_change_events_status_to_string.php',
    ];

    private User $admin;

    private EventCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', [
            '--path' => array_map(fn ($file) => 'database/migrations/'.$file, self::MIGRATIONS),
        ])->assertSuccessful();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::create(['name' => 'view events']);

        $this->admin = User::factory()->create();
        $this->admin->givePermissionTo('view events');

        $this->category = EventCategory::create(['name' => 'General']);
    }

    public static function statuses(): array
    {
        return array_combine(Event::STATUSES, array_map(fn ($s) => [$s], Event::STATUSES));
    }

    public function test_draft_event_can_be_created(): void
    {
        $response = $this->actingAs($this->admin)->post(route('events.store'), [
            'event_category_id' => $this->category->id,
            'title' => 'Planning meeting',
            'event_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', ['title' => 'Planning meeting', 'status' => 'draft']);
    }

    #[DataProvider('statuses')]
    public function test_event_can_be_created_with_status(string $status): void
    {
        $this->actingAs($this->admin)->post(route('events.store'), [
            'event_category_id' => $this->category->id,
            'title' => "Event {$status}",
            'event_date' => '2026-12-01',
            'status' => $status,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('events', ['title' => "Event {$status}", 'status' => $status]);
    }

    #[DataProvider('statuses')]
    public function test_event_can_be_updated_to_status(string $status): void
    {
        $event = Event::create([
            'event_category_id' => $this->category->id,
            'title' => 'Existing event',
            'event_date' => '2026-12-01',
        ]);

        $this->actingAs($this->admin)->put(route('events.update', $event), [
            'event_category_id' => $this->category->id,
            'title' => 'Existing event',
            'event_date' => '2026-12-01',
            'status' => $status,
        ])->assertSessionHasNoErrors();

        $this->assertSame($status, $event->fresh()->status);
    }

    public function test_status_outside_the_list_is_rejected(): void
    {
        $this->actingAs($this->admin)->post(route('events.store'), [
            'event_category_id' => $this->category->id,
            'title' => 'Bad status',
            'event_date' => '2026-12-01',
            'status' => 'published',
        ])->assertSessionHasErrors('status');

        $this->assertDatabaseMissing('events', ['title' => 'Bad status']);
    }

    public function test_new_event_defaults_to_upcoming(): void
    {
        $event = Event::create([
            'event_category_id' => $this->category->id,
            'title' => 'No status given',
            'event_date' => '2026-12-01',
        ]);

        $this->assertSame('upcoming', $event->fresh()->status);
    }

    public function test_edit_form_keeps_a_draft_selected(): void
    {
        $event = Event::create([
            'event_category_id' => $this->category->id,
            'title' => 'Draft event',
            'event_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->get(route('events.edit', $event));

        $response->assertOk();
        foreach (Event::STATUSES as $status) {
            $response->assertSee('value="'.$status.'"', false);
        }
        $this->assertMatchesRegularExpression('/value="draft"\s+selected/', $response->getContent());
    }
}

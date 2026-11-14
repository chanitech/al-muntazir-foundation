<?php

namespace Tests\Feature\Website;

use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\NewsletterSubscriber;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Program;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Concerns\RefreshesWebsiteDatabase;
use Tests\TestCase;

class AdminWebsiteTest extends TestCase
{
    use RefreshesWebsiteDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_guests_and_users_without_permission_cannot_manage_the_website(): void
    {
        $this->get('/admin/sliders')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get('/admin/sliders')
            ->assertForbidden();
    }

    public function test_the_migration_gives_existing_admin_roles_the_website_permission(): void
    {
        $this->assertTrue(Role::findByName('Website Editor', 'web')->hasPermissionTo('manage website'));
        $this->assertTrue(Permission::where('name', 'manage website')->exists());
    }

    public function test_every_website_admin_screen_renders(): void
    {
        $this->actingAs($this->websiteEditor());

        $slide = Slider::create(['title' => 'Slide', 'image' => 'website/sliders/a.jpg']);
        $program = Program::create(['title' => 'Program']);
        $post = Post::create(['title' => 'Post', 'body' => '<p>x</p>']);
        $person = TeamMember::create(['name' => 'Person']);
        $quote = Testimonial::create(['name' => 'Name', 'quote' => 'Quote']);
        $photo = GalleryItem::create(['image' => 'website/gallery/a.jpg']);
        $partner = Partner::create(['name' => 'Partner']);
        $stat = ImpactStat::create(['label' => 'Members', 'value' => 10]);
        $message = ContactMessage::create(['name' => 'A', 'email' => 'a@example.com', 'message' => 'Hello there friends']);
        NewsletterSubscriber::create(['email' => 'reader@example.com']);

        $urls = [
            '/admin/website-settings',
            '/admin/sliders', '/admin/sliders/create', "/admin/sliders/{$slide->id}/edit",
            '/admin/programs', '/admin/programs/create', "/admin/programs/{$program->id}/edit",
            '/admin/news', '/admin/news/create', "/admin/news/{$post->id}/edit",
            '/admin/team', '/admin/team/create', "/admin/team/{$person->id}/edit",
            '/admin/testimonials', '/admin/testimonials/create', "/admin/testimonials/{$quote->id}/edit",
            '/admin/gallery', '/admin/gallery/create', "/admin/gallery/{$photo->id}/edit",
            '/admin/partners', '/admin/partners/create', "/admin/partners/{$partner->id}/edit",
            '/admin/impact-stats', '/admin/impact-stats/create', "/admin/impact-stats/{$stat->id}/edit",
            '/admin/messages', "/admin/messages/{$message->id}",
            '/admin/subscribers',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_a_slide_can_be_added_replaced_hidden_and_deleted(): void
    {
        $this->actingAs($this->websiteEditor());

        $this->post('/admin/sliders', [
            'title' => 'Welcome',
            'subtitle' => 'Join us',
            'image' => UploadedFile::fake()->image('slide.jpg', 2400, 1200),
            'button_text' => 'Programs',
            'button_link' => '/programs',
            'sort_order' => '',
            'is_active' => '1',
        ])->assertRedirect(route('admin.sliders.index'));

        $slide = Slider::sole();
        Storage::disk('public')->assertExists($slide->image);
        $this->assertSame(0, $slide->sort_order);
        // Large photos are scaled down to 1920 px wide.
        $this->assertSame(1920, getimagesize(Storage::disk('public')->path($slide->image))[0]);

        $oldImage = $slide->image;
        $this->put("/admin/sliders/{$slide->id}", [
            'title' => 'Welcome back',
            'image' => UploadedFile::fake()->image('new.jpg', 800, 400),
            'is_active' => '1',
        ])->assertRedirect();

        $slide->refresh();
        $this->assertSame('Welcome back', $slide->title);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($slide->image);

        $this->patch("/admin/sliders/{$slide->id}/toggle")->assertRedirect();
        $this->assertFalse($slide->fresh()->is_active);

        $this->delete("/admin/sliders/{$slide->id}")->assertRedirect();
        $this->assertSame(0, Slider::count());
        Storage::disk('public')->assertMissing($slide->image);
    }

    public function test_a_slide_needs_an_image_and_a_safe_link(): void
    {
        $this->actingAs($this->websiteEditor());

        $this->post('/admin/sliders', ['title' => 'No image', 'button_link' => 'javascript:alert(1)'])
            ->assertSessionHasErrors(['image', 'button_link']);

        $this->assertSame(0, Slider::count());
    }

    public function test_article_html_is_cleaned_and_author_and_date_are_set(): void
    {
        $editor = $this->websiteEditor();
        $this->actingAs($editor);

        $this->post('/admin/news', [
            'title' => 'Our Youth Camp',
            'body' => '<p onclick="steal()">Great <strong>week</strong></p><script>alert(1)</script><a href="javascript:alert(1)">bad link</a>',
            'is_published' => '1',
        ])->assertRedirect(route('admin.posts.index'));

        $post = Post::sole();
        $this->assertSame('our-youth-camp', $post->slug);
        $this->assertSame($editor->id, $post->user_id);
        $this->assertNotNull($post->published_at);
        $this->assertStringContainsString('<strong>week</strong>', $post->body);
        $this->assertStringNotContainsString('script', $post->body);
        $this->assertStringNotContainsString('onclick', $post->body);
        $this->assertStringNotContainsString('javascript:', $post->body);

        $this->patch("/admin/news/{$post->id}/toggle");
        $this->assertFalse($post->fresh()->is_published);
        $this->get('/news/'.$post->slug)->assertNotFound();
    }

    public function test_several_gallery_photos_can_be_uploaded_at_once(): void
    {
        $this->actingAs($this->websiteEditor());

        $this->post('/admin/gallery', [
            'images' => [
                UploadedFile::fake()->image('a.jpg'),
                UploadedFile::fake()->image('b.png'),
                UploadedFile::fake()->image('c.webp'),
            ],
            'category' => 'Youth Camp',
            'is_active' => '1',
        ])->assertRedirect(route('admin.gallery.index'));

        $this->assertSame(3, GalleryItem::where('category', 'Youth Camp')->count());
        GalleryItem::all()->each(fn ($item) => Storage::disk('public')->assertExists($item->image));
    }

    public function test_site_settings_can_be_updated_including_the_logo(): void
    {
        $this->actingAs($this->websiteEditor());

        $this->put('/admin/website-settings', [
            'site_name' => 'Al-Muntadhir Youth Forum',
            'tagline' => 'New tagline',
            'primary_color' => '#0a0a0a',
            'accent_color' => '#f2b90c',
            'dark_color' => '#0b1f3a',
            'contact_email' => 'info@ayfc.or.tz',
            'facebook_url' => 'https://facebook.com/ayfc',
            'logo' => UploadedFile::fake()->image('logo.png', 400, 400),
            'active_tab' => 'branding',
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'branding']));

        $settings = app(SiteSettings::class);
        $this->assertSame('New tagline', $settings->get('tagline'));
        $this->assertSame('info@ayfc.or.tz', $settings->get('contact_email'));
        Storage::disk('public')->assertExists($settings->get('logo'));

        $this->get('/')->assertSee('New tagline')->assertSee('https://facebook.com/ayfc', false);
    }

    public function test_invalid_settings_are_rejected(): void
    {
        $this->actingAs($this->websiteEditor());

        $this->put('/admin/website-settings', [
            'site_name' => '',
            'primary_color' => 'blue',
            'accent_color' => '#f2b90c',
            'dark_color' => '#0b1f3a',
            'facebook_url' => 'javascript:alert(1)',
            'map_embed_url' => 'https://evil.example/maps/embed',
        ])->assertSessionHasErrors(['site_name', 'primary_color', 'facebook_url', 'map_embed_url']);
    }

    public function test_messages_are_marked_read_when_opened(): void
    {
        $this->actingAs($this->websiteEditor());
        $message = ContactMessage::create(['name' => 'Asha', 'email' => 'asha@example.com', 'message' => 'Please call me back.']);

        $this->get('/admin/messages?filter=unread')->assertSee('Asha');
        $this->get("/admin/messages/{$message->id}")->assertOk()->assertSee('Please call me back.');
        $this->assertTrue($message->fresh()->isRead());

        $this->patch("/admin/messages/{$message->id}/unread");
        $this->assertFalse($message->fresh()->isRead());

        $this->delete("/admin/messages/{$message->id}");
        $this->assertSame(0, ContactMessage::count());
    }

    public function test_subscribers_can_be_exported_safely_as_csv(): void
    {
        $this->actingAs($this->websiteEditor());
        NewsletterSubscriber::create(['email' => 'reader@example.com']);
        NewsletterSubscriber::create(['email' => '=cmd@example.com']);

        $csv = $this->get('/admin/subscribers/export')->assertOk()->streamedContent();

        $this->assertStringContainsString('reader@example.com', $csv);
        $this->assertStringContainsString("'=cmd@example.com", $csv);
    }

    public function test_editor_images_are_uploaded_and_return_a_url(): void
    {
        $this->actingAs($this->websiteEditor());

        $url = $this->postJson('/admin/editor-upload', ['image' => UploadedFile::fake()->image('photo.jpg')])
            ->assertOk()
            ->json('url');

        $this->assertStringContainsString('/storage/website/editor/', $url);
    }

    public function test_an_event_can_be_published_on_the_website_with_a_cover_image(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(Permission::findOrCreate('view events', 'web'));
        $this->actingAs($user);
        $category = EventCategory::create(['name' => 'Workshops', 'is_active' => true]);

        $this->post('/admin/events', [
            'event_category_id' => $category->id,
            'title' => 'Open Day',
            'event_date' => now()->addWeek()->toDateString(),
            'status' => 'upcoming',
            'is_public' => '1',
            'image' => UploadedFile::fake()->image('event.jpg'),
        ])->assertRedirect(route('events.index'));

        $event = Event::sole();
        $this->assertTrue($event->is_public);
        Storage::disk('public')->assertExists($event->image);
        $this->get('/events')->assertSee('Open Day');

        // Unticking the box hides it again; other details are kept.
        $this->put("/admin/events/{$event->id}", [
            'event_category_id' => $category->id,
            'title' => 'Open Day',
            'event_date' => $event->event_date->toDateString(),
            'status' => 'upcoming',
        ])->assertRedirect();

        $this->assertFalse($event->fresh()->is_public);
        $this->get('/events/'.$event->id)->assertNotFound();
    }
}

<?php

namespace Tests\Feature\Website;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\Post;
use App\Models\Program;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\SiteSettings;
use Tests\Concerns\RefreshesWebsiteDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshesWebsiteDatabase;

    public function test_every_public_page_works_on_an_empty_website(): void
    {
        foreach (['/', '/about', '/programs', '/news', '/events', '/gallery', '/contact'] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/')
            ->assertSee('Al-Muntadhir Youth Forum')
            ->assertSee('You shall have the upper hand, should you be faithful.')
            ->assertSee(route('login'), false);
    }

    public function test_homepage_shows_only_visible_slides_in_order(): void
    {
        Slider::create(['title' => 'Second slide', 'image' => 'website/sliders/b.jpg', 'sort_order' => 2, 'is_active' => true]);
        Slider::create(['title' => 'First slide', 'image' => 'website/sliders/a.jpg', 'sort_order' => 1, 'is_active' => true]);
        Slider::create(['title' => 'Hidden slide', 'image' => 'website/sliders/c.jpg', 'sort_order' => 0, 'is_active' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder(['First slide', 'Second slide'])
            ->assertDontSee('Hidden slide')
            ->assertSee('/storage/website/sliders/a.jpg', false);
    }

    public function test_homepage_lists_programs_numbers_news_and_testimonials(): void
    {
        Program::create(['title' => 'Leadership Lab', 'summary' => 'Learn to lead.', 'is_active' => true]);
        Program::create(['title' => 'Secret Program', 'is_active' => false]);
        ImpactStat::create(['label' => 'Active members', 'value' => 450, 'suffix' => '+', 'is_active' => true]);
        Post::create(['title' => 'Published news', 'body' => '<p>Hi</p>', 'is_published' => true, 'published_at' => now()->subDay()]);
        Post::create(['title' => 'Draft news', 'body' => '<p>Hi</p>', 'is_published' => false]);
        Post::create(['title' => 'Scheduled news', 'body' => '<p>Hi</p>', 'is_published' => true, 'published_at' => now()->addWeek()]);
        Testimonial::create(['name' => 'Amina', 'quote' => 'A wonderful forum.', 'is_active' => true]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Leadership Lab')
            ->assertDontSee('Secret Program')
            ->assertSee('Active members')
            ->assertSee('data-count="450"', false)
            ->assertSee('Published news')
            ->assertDontSee('Draft news')
            ->assertDontSee('Scheduled news')
            ->assertSee('A wonderful forum.');
    }

    public function test_program_page_and_hidden_program(): void
    {
        $program = Program::create(['title' => 'Dialogue for Change', 'body' => '<h2>Sessions</h2><p>Weekly.</p>', 'is_active' => true]);
        $hidden = Program::create(['title' => 'Old Program', 'is_active' => false]);

        $this->assertSame('dialogue-for-change', $program->slug);

        $this->get('/programs/dialogue-for-change')->assertOk()->assertSee('<h2>Sessions</h2>', false);
        $this->get('/programs/'.$hidden->slug)->assertNotFound();
    }

    public function test_slugs_are_unique_and_stay_the_same_when_the_title_changes(): void
    {
        $first = Program::create(['title' => 'Youth Camp']);
        $second = Program::create(['title' => 'Youth Camp']);

        $this->assertSame('youth-camp', $first->slug);
        $this->assertSame('youth-camp-2', $second->slug);

        $first->update(['title' => 'Youth Camp 2027']);
        $this->assertSame('youth-camp', $first->fresh()->slug);
    }

    public function test_news_article_pages(): void
    {
        $post = Post::create(['title' => 'Camp Highlights', 'body' => '<p>Great week.</p>', 'is_published' => true, 'published_at' => now()]);
        $draft = Post::create(['title' => 'Unfinished', 'body' => '<p>x</p>', 'is_published' => false]);

        $this->get('/news')->assertOk()->assertSee('Camp Highlights')->assertDontSee('Unfinished');
        $this->get('/news?q=camp')->assertOk()->assertSee('Camp Highlights');
        $this->get('/news?q=nothing-matches')->assertOk()->assertDontSee('Camp Highlights');
        $this->get('/news/'.$post->slug)->assertOk()->assertSee('Great week.');
        $this->get('/news/'.$draft->slug)->assertNotFound();
    }

    public function test_only_events_marked_public_appear_on_the_website(): void
    {
        $category = EventCategory::create(['name' => 'Workshops', 'is_active' => true]);
        $public = Event::create(['event_category_id' => $category->id, 'title' => 'Open Workshop', 'event_date' => now()->addDays(3)->toDateString(), 'status' => 'upcoming', 'is_public' => true]);
        $private = Event::create(['event_category_id' => $category->id, 'title' => 'Committee Meeting', 'event_date' => now()->addDays(2)->toDateString(), 'status' => 'upcoming', 'is_public' => false]);
        Event::create(['event_category_id' => $category->id, 'title' => 'Last Year Camp', 'event_date' => now()->subMonth()->toDateString(), 'status' => 'completed', 'is_public' => true]);

        $this->get('/events')
            ->assertOk()
            ->assertSee('Open Workshop')
            ->assertSee('Last Year Camp')
            ->assertDontSee('Committee Meeting');

        $this->get('/')->assertSee('Open Workshop')->assertDontSee('Committee Meeting');
        $this->get('/events/'.$public->id)->assertOk()->assertSee('Workshops');
        $this->get('/events/'.$private->id)->assertNotFound();
    }

    public function test_about_page_shows_mission_values_and_team(): void
    {
        TeamMember::create(['name' => 'Hassan Ali', 'position' => 'Chairperson', 'is_active' => true]);
        TeamMember::create(['name' => 'Former Member', 'is_active' => false]);
        app(SiteSettings::class)->save(['core_values' => "Faith: rooted in belief\nService"]);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Our Mission')
            ->assertSee('rooted in belief')
            ->assertSee('Hassan Ali')
            ->assertSee('HA')
            ->assertDontSee('Former Member');
    }

    public function test_gallery_can_be_filtered_by_album(): void
    {
        GalleryItem::create(['title' => 'Camp photo', 'category' => 'Camp', 'image' => 'website/gallery/1.jpg', 'is_active' => true]);
        GalleryItem::create(['title' => 'Iftar photo', 'category' => 'Iftar', 'image' => 'website/gallery/2.jpg', 'is_active' => true]);

        $this->get('/gallery')->assertOk()->assertSee('Camp photo')->assertSee('Iftar photo');
        $this->get('/gallery?category=Camp')->assertOk()->assertSee('Camp photo')->assertDontSee('Iftar photo');
    }

    public function test_saved_settings_replace_the_defaults(): void
    {
        app(SiteSettings::class)->save([
            'site_name' => 'AYFC Tanzania',
            'contact_email' => 'info@ayfc.or.tz',
            'primary_color' => '#123456',
            'contact_whatsapp' => '+255 700 111 222',
            'tagline' => '',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('AYFC Tanzania')
            ->assertSee('info@ayfc.or.tz')
            ->assertSee('--brand: #123456;', false)
            ->assertSee('https://wa.me/255700111222', false)
            // An emptied text falls back to the default instead of disappearing.
            ->assertSee('Empowering young minds');
    }
}

<?php

namespace Tests\Feature\Website;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Tests\Concerns\RefreshesWebsiteDatabase;
use Tests\TestCase;

class ContactAndNewsletterTest extends TestCase
{
    use RefreshesWebsiteDatabase;

    public function test_a_visitor_can_send_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'Asha',
            'email' => 'asha@example.com',
            'phone' => '+255 700 000 001',
            'subject' => 'Membership',
            'message' => 'I would like to join the forum, please.',
        ])->assertRedirect('/contact')->assertSessionHas('success');

        $message = ContactMessage::sole();
        $this->assertSame('Asha', $message->name);
        $this->assertSame('Membership', $message->subject);
        $this->assertFalse($message->isRead());
    }

    public function test_the_message_is_validated(): void
    {
        $this->from('/contact')
            ->post('/contact', ['name' => '', 'email' => 'not-an-email', 'message' => 'short'])
            ->assertRedirect('/contact')
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_spam_bots_filling_the_hidden_field_are_ignored(): void
    {
        $this->post('/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Buy cheap things now!!!',
            'website' => 'http://spam.example',
        ])->assertRedirect('/contact');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_the_subject_can_be_preselected_from_a_link(): void
    {
        $this->get('/contact?subject=Membership')
            ->assertOk()
            ->assertSee('<option value="Membership" selected', false);
    }

    public function test_newsletter_signup_is_stored_once_in_lowercase(): void
    {
        $this->from('/')->post('/newsletter', ['email' => 'Reader@Example.COM'])->assertRedirect('/')->assertSessionHas('newsletter_success');
        $this->from('/')->post('/newsletter', ['email' => 'reader@example.com'])->assertRedirect('/');

        $this->assertSame(['reader@example.com'], NewsletterSubscriber::pluck('email')->all());
    }

    public function test_an_invalid_newsletter_email_is_rejected(): void
    {
        $this->from('/')->post('/newsletter', ['email' => 'nope'])
            ->assertRedirect('/')
            ->assertSessionHasErrorsIn('newsletter', 'email');

        $this->assertSame(0, NewsletterSubscriber::count());
    }
}

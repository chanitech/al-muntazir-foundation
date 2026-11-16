<?php

namespace Tests\Unit;

use App\Rules\SafeLink;
use App\Support\HtmlSanitizer;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_formatting_is_kept(): void
    {
        $html = '<h2>Title</h2><p style="text-align: center; color: red">Hello <strong>world</strong> <a href="https://ayfc.or.tz" target="_blank">link</a></p><ul><li>One</li></ul>';
        $clean = HtmlSanitizer::clean($html);

        $this->assertStringContainsString('<h2>Title</h2>', $clean);
        $this->assertStringContainsString('<p style="text-align: center;">', $clean);
        $this->assertStringContainsString('<strong>world</strong>', $clean);
        $this->assertStringContainsString('rel="noopener noreferrer"', $clean);
        $this->assertStringContainsString('<li>One</li>', $clean);
        $this->assertStringNotContainsString('color', $clean);
    }

    public function test_dangerous_markup_is_removed(): void
    {
        $clean = HtmlSanitizer::clean(
            '<p onmouseover="x()">Hi</p><script>alert(1)</script><style>body{}</style>'
            .'<a href=" javascript:alert(1)">a</a><a href="JaVaScRiPt:alert(1)">b</a>'
            .'<img src="data:image/png;base64,AAA" onerror="x()"><iframe src="https://evil.example/x"></iframe>'
            .'<svg onload="x()"></svg><form action="/x"><input name="a"></form><!-- comment -->'
        );

        foreach (['onmouseover', '<script', 'alert', '<style', 'javascript', 'data:', 'onerror', 'evil.example', '<svg', '<form', '<input', 'comment'] as $needle) {
            $this->assertStringNotContainsStringIgnoringCase($needle, $clean, "Found \"{$needle}\" in: {$clean}");
        }
        $this->assertStringContainsString('<p>Hi</p>', $clean);
    }

    public function test_youtube_embeds_and_unicode_text_survive(): void
    {
        $clean = HtmlSanitizer::clean('<p>Karibu sana – السلام عليكم</p><iframe src="https://www.youtube.com/embed/abc123" allowfullscreen></iframe>');

        $this->assertStringContainsString('Karibu sana – السلام عليكم', $clean);
        $this->assertStringContainsString('src="https://www.youtube.com/embed/abc123"', $clean);
    }

    public function test_unknown_tags_are_unwrapped_but_their_text_is_kept(): void
    {
        $this->assertSame('<p>Keep this text</p>', HtmlSanitizer::clean('<p><font color="red">Keep</font> this <marquee>text</marquee></p>'));
        $this->assertSame('', HtmlSanitizer::clean(null));
    }

    public function test_safe_link_rule(): void
    {
        $valid = ['/programs', '/contact?subject=Membership', '#about', 'https://ayfc.or.tz', 'mailto:info@ayfc.or.tz', 'tel:+255700000000', ''];
        $invalid = ['javascript:alert(1)', 'data:text/html,hi', '//evil.example', 'programs', 'ftp://example.com'];

        foreach ($valid as $link) {
            $this->assertTrue(Validator::make(['link' => $link], ['link' => [new SafeLink]])->passes(), "Expected \"{$link}\" to be accepted");
        }
        foreach ($invalid as $link) {
            $this->assertFalse(Validator::make(['link' => $link], ['link' => [new SafeLink]])->passes(), "Expected \"{$link}\" to be rejected");
        }
    }
}

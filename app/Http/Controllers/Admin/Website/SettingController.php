<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Rules\SafeLink;
use App\Services\Website\ImageUploader;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Website → Site Settings: name, logo, colours, contacts, social links, about text... */
class SettingController extends Controller
{
    public function __construct(private SiteSettings $settings, private ImageUploader $uploader) {}

    public function edit(): View
    {
        return view('admin.website.settings.edit', ['values' => $this->settings->all()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $color = ['required', 'regex:/^#[0-9a-fA-F]{6}$/'];
        $text = fn (int $max = 255) => ['nullable', 'string', 'max:'.$max];
        $url = ['nullable', 'url:http,https', 'max:255'];
        $image = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

        $rules = [
            // General
            'site_name' => ['required', 'string', 'max:120'],
            'site_short_name' => $text(30),
            'tagline' => $text(160),
            'motto' => $text(255),
            'motto_source' => $text(80),
            'footer_about' => $text(500),
            'meta_description' => $text(300),
            // Branding
            'primary_color' => $color,
            'accent_color' => $color,
            'dark_color' => $color,
            'logo' => $image,
            'favicon' => ['nullable', 'file', 'mimes:png,ico,jpg,jpeg', 'max:1024'],
            // Contact
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => $text(60),
            'contact_whatsapp' => $text(60),
            'address' => $text(500),
            'office_hours' => $text(255),
            'map_embed_url' => ['nullable', 'url:https', 'max:2000', 'regex:#^https://(www\.)?google\.[a-z.]+/maps/embed#i'],
            // Social
            'facebook_url' => $url,
            'instagram_url' => $url,
            'x_url' => $url,
            'youtube_url' => $url,
            'tiktok_url' => $url,
            'linkedin_url' => $url,
            // About
            'about_title' => $text(160),
            'about_text' => $text(5000),
            'about_image' => $image,
            'mission' => $text(1000),
            'vision' => $text(1000),
            'core_values' => $text(1000),
            // Homepage
            'hero_title' => $text(160),
            'hero_subtitle' => $text(300),
            'hero_image' => $image,
            'programs_title' => $text(120),
            'programs_intro' => $text(300),
            'stats_title' => $text(120),
            'events_title' => $text(120),
            'news_title' => $text(120),
            'cta_title' => $text(160),
            'cta_text' => $text(300),
            'cta_button_text' => $text(40),
            'cta_button_link' => ['nullable', 'string', 'max:255', new SafeLink],
        ];

        $validated = $request->validate($rules, [
            'map_embed_url.regex' => 'Paste the link from Google Maps → Share → Embed a map (it starts with https://www.google.com/maps/embed).',
            'primary_color.regex' => 'Colours must be in the format #RRGGBB.',
            'accent_color.regex' => 'Colours must be in the format #RRGGBB.',
            'dark_color.regex' => 'Colours must be in the format #RRGGBB.',
        ]);

        $current = $this->settings->all();
        $stale = [];

        foreach (config('website.image_settings') as $key) {
            unset($validated[$key]);

            if ($request->hasFile($key)) {
                $maxWidth = $key === 'favicon' ? 256 : ($key === 'logo' ? 600 : 1920);
                $validated[$key] = $this->uploader->store($request->file($key), 'branding', $maxWidth);
                $stale[] = $current[$key] ?? null;
            } elseif ($request->boolean('remove_'.$key)) {
                $validated[$key] = null;
                $stale[] = $current[$key] ?? null;
            }
        }

        $this->settings->save($validated);

        foreach (array_filter($stale) as $path) {
            $this->uploader->delete($path);
        }

        return redirect()
            ->route('admin.settings.edit', ['tab' => $request->input('active_tab', 'general')])
            ->with('success', 'Website settings saved.');
    }
}

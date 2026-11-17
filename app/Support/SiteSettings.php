<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Reads and writes the public website settings.
 *
 * Values saved in the admin panel override the defaults in config/website.php.
 * The merged result is cached and the cache is cleared whenever settings change.
 */
class SiteSettings
{
    private const CACHE_KEY = 'website.settings';

    private ?array $resolved = null;

    public function all(): array
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $saved = [];

        try {
            $saved = Cache::rememberForever(self::CACHE_KEY, function () {
                if (! Schema::hasTable('site_settings')) {
                    return [];
                }

                return SiteSetting::pluck('value', 'key')->all();
            });
        } catch (Throwable $e) {
            report($e);
        }

        $defaults = config('website.defaults', []);

        // An empty saved value means "use the default" for required texts,
        // but optional fields (contacts, socials...) may legitimately be blank.
        foreach ($saved as $key => $value) {
            if ($value === null || $value === '') {
                if (array_key_exists($key, $defaults) && $defaults[$key] !== null) {
                    unset($saved[$key]);
                }
            }
        }

        return $this->resolved = array_merge($defaults, $saved);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->all()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function __isset(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /** Public URL for an image setting such as "logo". */
    public function image(string $key, ?string $fallback = null): ?string
    {
        return Media::url($this->get($key)) ?? $fallback;
    }

    public function logoUrl(): string
    {
        return $this->image('logo', asset('images/logo.jpg'));
    }

    /** Lines of a multi-line setting, e.g. core values. */
    public function lines(string $key): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $this->get($key, '')))));
    }

    /** Paragraphs of a long text setting. */
    public function paragraphs(string $key): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/(\r\n|\r|\n){2,}/', (string) $this->get($key, '')))));
    }

    public function socialLinks(): array
    {
        $networks = [
            'facebook_url' => ['Facebook', 'bi-facebook'],
            'instagram_url' => ['Instagram', 'bi-instagram'],
            'x_url' => ['X (Twitter)', 'bi-twitter-x'],
            'youtube_url' => ['YouTube', 'bi-youtube'],
            'tiktok_url' => ['TikTok', 'bi-tiktok'],
            'linkedin_url' => ['LinkedIn', 'bi-linkedin'],
        ];

        $links = [];
        foreach ($networks as $key => [$label, $icon]) {
            if ($url = $this->get($key)) {
                $links[] = ['url' => $url, 'label' => $label, 'icon' => $icon];
            }
        }

        return $links;
    }

    public function whatsappUrl(): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $this->get('contact_whatsapp'));

        return $number ? 'https://wa.me/'.$number : null;
    }

    /**
     * CSS custom properties for the brand colours chosen in the admin panel,
     * plus derived shades and readable text colours.
     */
    public function cssVariables(): string
    {
        $defaults = config('website.defaults');
        $vars = [];

        foreach (['brand' => 'primary_color', 'accent' => 'accent_color', 'dark' => 'dark_color'] as $name => $key) {
            $hex = $this->get($key);
            if (! is_string($hex) || ! preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
                $hex = $defaults[$key];
            }

            [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
            $vars["--{$name}"] = strtolower($hex);
            $vars["--{$name}-rgb"] = "{$r}, {$g}, {$b}";
            $vars["--{$name}-shade"] = sprintf('#%02x%02x%02x', (int) ($r * 0.82), (int) ($g * 0.82), (int) ($b * 0.82));
            // Text that stays readable on top of this colour.
            $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
            $vars["--on-{$name}"] = $luminance > 0.6 ? '#111827' : '#ffffff';
        }

        return collect($vars)->map(fn ($value, $name) => "{$name}: {$value};")->implode(' ');
    }

    public function save(array $values): void
    {
        foreach ($values as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->flush();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->resolved = null;
    }
}

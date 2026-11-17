<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** A news article / blog post shown on the public website. */
class Post extends Model
{
    use HasSlug, WebsiteContent;

    protected $fillable = ['title', 'slug', 'image', 'excerpt', 'body', 'is_published', 'published_at', 'user_id'];

    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Published and not scheduled for the future. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function isLive(): bool
    {
        return $this->is_published && (! $this->published_at || $this->published_at->lte(now()));
    }

    public function displayDate(): Carbon
    {
        return $this->published_at ?? $this->created_at ?? now();
    }

    public function summary(int $limit = 160): string
    {
        return $this->excerpt ?: Str::limit(trim(html_entity_decode(strip_tags((string) $this->body))), $limit);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 200));
    }
}

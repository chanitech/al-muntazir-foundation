<?php

namespace App\Models\Concerns;

use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared behaviour for content managed from Admin → Website:
 * an "is_active" switch, a manual "sort_order" and image URLs.
 */
trait WebsiteContent
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->getTable().'.is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy($this->getTable().'.sort_order')->orderBy($this->getTable().'.id');
    }

    /** Public URL of an image column, or null when no image is set. */
    public function mediaUrl(string $column = 'image'): ?string
    {
        return Media::url($this->{$column});
    }
}

<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Generates a unique URL slug from the title when none is given.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            // An existing slug is kept even when the title changes, so links stay valid.
            $base = Str::slug((string) ($model->slug ?: $model->title)) ?: Str::lower(Str::random(8));
            $slug = $base;
            $i = 2;

            while (static::where('slug', $slug)->when($model->exists, fn ($q) => $q->whereKeyNot($model->getKey()))->exists()) {
                $slug = $base.'-'.$i++;
            }

            $model->slug = $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

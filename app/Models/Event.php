<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_category_id',
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'status',
        'sms_sent',
        'sms_sent_at',
        'is_recurring',
        'recurrence_pattern',
        'priority',
        'is_public',
        'is_archived',
        'sms_template',
        'image',
        'created_by',
        'updated_by', 
        'deleted_by',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'sms_sent' => 'boolean',
        'is_recurring' => 'boolean',
        'is_public' => 'boolean',
        'is_archived' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Public website
    |--------------------------------------------------------------------------
    */

    /** Events ticked "Show on public website" that are not drafts. */
    public function scopePublicListing(Builder $query): Builder
    {
        return $query->where('is_public', true)->where('status', '!=', 'draft');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('event_date', '>=', today())
            ->where('status', '!=', 'completed')
            ->orderBy('event_date')
            ->orderBy('start_time');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where(fn (Builder $q) => $q
            ->whereDate('event_date', '<', today())
            ->orWhere('status', 'completed'))
            ->orderByDesc('event_date');
    }

    public function isVisibleOnWebsite(): bool
    {
        return (bool) $this->is_public && $this->status !== 'draft';
    }

    public function imageUrl(): ?string
    {
        return Media::url($this->image);
    }

    /** e.g. "9:00 AM – 1:00 PM", or null when no time is set. */
    public function timeRange(): ?string
    {
        if (! $this->start_time) {
            return null;
        }

        $range = $this->start_time->format('g:i A');

        return $this->end_time ? $range.' – '.$this->end_time->format('g:i A') : $range;
    }
}

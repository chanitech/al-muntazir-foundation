<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Statuses an event can have. The admin create/edit forms and the
     * EventController validation rules both read this list, so they stay in sync.
     */
    public const STATUSES = ['upcoming', 'ongoing', 'completed', 'cancelled', 'draft'];

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
}
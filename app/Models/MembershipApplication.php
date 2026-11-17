<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** A membership application sent from the website "Join Us" form. */
class MembershipApplication extends Model
{
    public const PENDING = 'pending';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'date_of_birth', 'phone', 'email', 'national_id',
        'membership_type_id', 'current_region', 'current_district', 'current_village',
        'education', 'occupation', 'motivation',
        'status', 'review_note', 'reviewed_by', 'reviewed_at', 'member_id', 'ip_address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::PENDING);
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function fullName(): string
    {
        return trim(preg_replace('/\s+/', ' ', $this->first_name.' '.$this->middle_name.' '.$this->last_name));
    }

    /** Reference shown to the applicant, e.g. APP-000012. */
    public function reference(): string
    {
        return 'APP-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /** An existing (not deleted) member already registered with this phone number. */
    public function existingMember(): ?Member
    {
        return Member::where('phone', $this->phone)->first();
    }
}

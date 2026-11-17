<?php

namespace App\Models;

use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use WebsiteContent;

    protected $fillable = [
        'name', 'position', 'photo', 'bio', 'email',
        'facebook_url', 'instagram_url', 'x_url', 'linkedin_url',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))
            ->filter()->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}

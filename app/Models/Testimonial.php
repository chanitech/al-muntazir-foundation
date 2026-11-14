<?php

namespace App\Models;

use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use WebsiteContent;

    protected $fillable = ['name', 'role', 'quote', 'photo', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}

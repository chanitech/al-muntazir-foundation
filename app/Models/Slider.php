<?php

namespace App\Models;

use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use WebsiteContent;

    protected $fillable = ['title', 'subtitle', 'image', 'button_text', 'button_link', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}

<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasSlug, WebsiteContent;

    protected $fillable = ['title', 'slug', 'icon', 'image', 'schedule', 'summary', 'body', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}

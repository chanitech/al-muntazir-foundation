<?php

namespace App\Models;

use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use WebsiteContent;

    protected $fillable = ['title', 'category', 'image', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}

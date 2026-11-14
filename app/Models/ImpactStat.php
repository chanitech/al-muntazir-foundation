<?php

namespace App\Models;

use App\Models\Concerns\WebsiteContent;
use Illuminate\Database\Eloquent\Model;

/** A headline number shown in the "Our Impact" band, e.g. "450+ Members". */
class ImpactStat extends Model
{
    use WebsiteContent;

    protected $fillable = ['label', 'value', 'suffix', 'icon', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer', 'value' => 'integer'];
}

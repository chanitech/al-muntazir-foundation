<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One key/value pair from Admin → Website → Site Settings. */
class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];
}

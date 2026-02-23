<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionPosition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'election_id',
        'position_id',
        'is_open'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Very Useful Later)
    |--------------------------------------------------------------------------
    */

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    /** Active and inside the voting period right now. */
    public function isOpen(): bool
    {
        return $this->is_active
            && (! $this->start_time || now()->gte($this->start_time))
            && (! $this->end_time || now()->lte($this->end_time));
    }

    public function positions()
{
    return $this->belongsToMany(Position::class, 'election_positions');
}
}
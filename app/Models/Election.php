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

    public function positions()
{
    return $this->belongsToMany(Position::class, 'election_positions');
}
}
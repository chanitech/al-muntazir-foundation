<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    protected $fillable = ['name'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    /** True for "Married" (not for "Unmarried", "Not married" or "Never married"). */
    public function isMarried(): bool
    {
        $name = strtolower(trim((string) $this->name));

        return str_contains($name, 'married') && ! preg_match('/\b(un|not|never)\s*-?\s*married/', $name);
    }
}

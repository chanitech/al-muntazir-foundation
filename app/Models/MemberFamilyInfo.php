<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFamilyInfo extends Model
{
    protected $fillable = [
        'member_id',
        'father_name',
        'father_occupation',
        'mother_name',
        'mother_occupation',
        'guardian_name',
        'guardian_occupation',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
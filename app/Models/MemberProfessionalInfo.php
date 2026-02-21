<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfessionalInfo extends Model
{
    protected $fillable = [
        'member_id',
        'occupation',
        'employer',
        'work_address',
        'years_of_experience'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberAcademicBackground extends Model
{
    protected $fillable = [
        'member_id',
        'institution_name',
        'education_level',
        'start_year',
        'end_year',
        'certificate_file'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
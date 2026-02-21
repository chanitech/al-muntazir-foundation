<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'membership_number',
        'join_date',
        'membership_type_id',
        'status',
        'full_name',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'religion',
        'tribe',
        'national_id',
        'phone',
        'email',
        'address',
        'photo',
        'marital_status_id',
        'spouse_name',
        'spouse_phone',
        'children_count'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function familyInfo()
    {
        return $this->hasOne(MemberFamilyInfo::class);
    }

    public function academicBackgrounds()
    {
        return $this->hasMany(MemberAcademicBackground::class);
    }

    public function professionalInfos()
    {
        return $this->hasMany(MemberProfessionalInfo::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'member_skill');
    }

    public static function generateMembershipNumber()
{
    $lastMember = self::latest('id')->first();

    if (!$lastMember) {
        $number = 1;
    } else {
        $lastNumber = (int) substr($lastMember->membership_number, -6);
        $number = $lastNumber + 1;
    }

    return 'AYFC-' . str_pad($number, 6, '0', STR_PAD_LEFT);
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Member extends Model
{
    use SoftDeletes;

    protected $table = 'members';

    /*
    |--------------------------------------------------------------------------
    | Appends
    |--------------------------------------------------------------------------
    */

    protected $appends = [
        'photo_url'
    ];

    /*
    |--------------------------------------------------------------------------
    | Fillable Fields
    |--------------------------------------------------------------------------
    */

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

        'first_name',
        'middle_name',
        'last_name',

        'current_country',
        'current_region',
        'current_district',
        'current_village',

        'marital_status_id',
        'spouse_name',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_surname',
        'spouse_phone',
        'children_count',

        'is_employed',
        'employer_name',
        'job_title',
        'employment_status',
        'employer_address',

        /*
        Wizard JSON Storage (Enterprise Friendly)
        */

        'family_info',
        'academic_history',
        'professional_history'
    ];

    /*
    |--------------------------------------------------------------------------
    | Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'date_of_birth' => 'date',
        'join_date' => 'date',

        'children_count' => 'integer',
        'is_employed' => 'boolean',

        'family_info' => 'array',
        'academic_history' => 'array',
        'professional_history' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** The login account of this member (used for voting and roles). */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'member_skill');
    }

    /*
    |--------------------------------------------------------------------------
    | Photo URL Accessor
    |--------------------------------------------------------------------------
    */

    protected function photoUrl(): Attribute
    {
        return Attribute::get(function () {

            if (!$this->photo) {
                return asset('images/default.png');
            }

            return asset('storage/photos/'.$this->photo);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Membership Number Generator (Concurrency Safe ⭐⭐⭐⭐⭐)
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Membership Number Generator (Enterprise Safe)
|--------------------------------------------------------------------------
*/

public static function generateMembershipNumber(): string
{
    // Must run inside a database transaction (the row lock prevents two
    // registrations from getting the same number). Deleted members are
    // included so their numbers are never handed out again.
    $last = self::withTrashed()
        ->lockForUpdate()
        ->orderBy('id', 'desc')
        ->value('membership_number');

    $nextNumber = $last ? ((int) Str::substr($last, -6)) + 1 : 1;

    do {
        $number = 'AYFC-' . str_pad($nextNumber++, 6, '0', STR_PAD_LEFT);
    } while (self::withTrashed()->where('membership_number', $number)->exists());

    return $number;
}

public function academicCount(): int
{
    return collect($this->academic_history ?? [])->count();
}

public function professionalCount(): int
{
    return collect($this->professional_history ?? [])->count();
}
}
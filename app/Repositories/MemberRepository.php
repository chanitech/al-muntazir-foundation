<?php

namespace App\Repositories;

use App\Models\Member;

class MemberRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Member();
    }

    public function getProfile($id)
    {
        return $this->model->with([
            'membershipType',
            'maritalStatus',
            'familyInfo',
            'academicBackgrounds',
            'professionalInfos',
            'skills'
        ])->findOrFail($id);
    }
}
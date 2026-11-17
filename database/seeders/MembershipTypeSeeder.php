<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MembershipType;

class MembershipTypeSeeder extends Seeder
{
    public function run()
    {
        MembershipType::firstOrCreate(
            ['name' => 'Default Member'],
            ['is_active' => true]
        );
    }
}



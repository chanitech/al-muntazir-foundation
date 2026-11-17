<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        // All seeders below are safe to run more than once.
        $this->call(MembershipTypeSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(DistrictSeeder::class);

        // A sample login for local development only.
        if (app()->environment('local')) {
            User::firstOrCreate(
                ['email' => 'test@example.com'],
                ['name' => 'Test User', 'password' => bcrypt('password')]
            );
        }
    }
}

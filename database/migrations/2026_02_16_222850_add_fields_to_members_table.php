<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This file is dated before the members table is created. On a fresh
        // install it is skipped here and the columns are added later by
        // 2026_11_15_000000_complete_membership_schema.
        if (! Schema::hasTable('members')) {
            return;
        }

        $columns = [
            'current_country' => fn (Blueprint $t) => $t->string('current_country')->nullable(),
            'current_region' => fn (Blueprint $t) => $t->string('current_region')->nullable(),
            'current_district' => fn (Blueprint $t) => $t->string('current_district')->nullable(),
            'current_village' => fn (Blueprint $t) => $t->string('current_village')->nullable(),
            'spouse_first_name' => fn (Blueprint $t) => $t->string('spouse_first_name')->nullable(),
            'spouse_middle_name' => fn (Blueprint $t) => $t->string('spouse_middle_name')->nullable(),
            'spouse_surname' => fn (Blueprint $t) => $t->string('spouse_surname')->nullable(),
            'is_employed' => fn (Blueprint $t) => $t->boolean('is_employed')->default(false),
            'employer_name' => fn (Blueprint $t) => $t->string('employer_name')->nullable(),
            'job_title' => fn (Blueprint $t) => $t->string('job_title')->nullable(),
            'employment_status' => fn (Blueprint $t) => $t->string('employment_status')->nullable(), // full time, part time, contract, intern
            'employer_address' => fn (Blueprint $t) => $t->text('employer_address')->nullable(),
        ];

        foreach ($columns as $name => $define) {
            if (! Schema::hasColumn('members', $name)) {
                Schema::table('members', fn (Blueprint $table) => $define($table));
            }
        }

        // The old single spouse_name column was replaced by the name parts above.
        if (Schema::hasColumn('members', 'spouse_name')) {
            Schema::table('members', fn (Blueprint $table) => $table->dropColumn('spouse_name'));
        }
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'current_country',
                'current_region',
                'current_district',
                'current_village',
                'spouse_first_name',
                'spouse_middle_name',
                'spouse_surname',
                'is_employed',
                'employer_name',
                'job_title',
                'employment_status',
                'employer_address',
            ]);
        });
    }
};
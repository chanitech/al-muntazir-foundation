<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            // Personal – Current Address
            $table->string('current_country')->nullable()->after('address');
            $table->string('current_region')->nullable()->after('current_country');
            $table->string('current_district')->nullable()->after('current_region');
            $table->string('current_village')->nullable()->after('current_district');

            // Family – Spouse name parts (remove old spouse_name)
            $table->string('spouse_first_name')->nullable()->after('spouse_phone');
            $table->string('spouse_middle_name')->nullable()->after('spouse_first_name');
            $table->string('spouse_surname')->nullable()->after('spouse_middle_name');
            
            // Employment
            $table->boolean('is_employed')->default(false)->after('professional_history');
            $table->string('employer_name')->nullable()->after('is_employed');
            $table->string('job_title')->nullable()->after('employer_name');
            $table->string('employment_status')->nullable()->after('job_title'); // full time, part time, contract, intern
            $table->text('employer_address')->nullable()->after('employment_status');
        });

        // If you want to drop the old spouse_name column (optional but recommended)
        Schema::table('members', function (Blueprint $table) {
             $table->dropColumn('spouse_name');
         });
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
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Adds the columns of 2026_02_16_222850_add_fields_to_members_table on databases where
 * that migration had nothing to alter, i.e. fresh installs (it is dated before `members`
 * exists). Every step is guarded, so on databases that already have these columns
 * (production) this migration does nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $missing = fn (string $column) => ! Schema::hasColumn('members', $column);

            // Personal – Current Address
            if ($missing('current_country')) {
                $table->string('current_country')->nullable()->after('address');
            }
            if ($missing('current_region')) {
                $table->string('current_region')->nullable()->after('current_country');
            }
            if ($missing('current_district')) {
                $table->string('current_district')->nullable()->after('current_region');
            }
            if ($missing('current_village')) {
                $table->string('current_village')->nullable()->after('current_district');
            }

            // Family – Spouse name parts (replace the old spouse_name)
            if ($missing('spouse_first_name')) {
                $table->string('spouse_first_name')->nullable()->after('spouse_phone');
            }
            if ($missing('spouse_middle_name')) {
                $table->string('spouse_middle_name')->nullable()->after('spouse_first_name');
            }
            if ($missing('spouse_surname')) {
                $table->string('spouse_surname')->nullable()->after('spouse_middle_name');
            }

            // Employment
            if ($missing('is_employed')) {
                $table->boolean('is_employed')->default(false)->after('professional_history');
            }
            if ($missing('employer_name')) {
                $table->string('employer_name')->nullable()->after('is_employed');
            }
            if ($missing('job_title')) {
                $table->string('job_title')->nullable()->after('employer_name');
            }
            if ($missing('employment_status')) {
                $table->string('employment_status')->nullable()->after('job_title'); // full time, part time, contract, intern
            }
            if ($missing('employer_address')) {
                $table->text('employer_address')->nullable()->after('employment_status');
            }
        });

        if (Schema::hasColumn('members', 'spouse_name')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('spouse_name');
            });
        }
    }

    public function down(): void
    {
        // Intentionally empty. On existing databases these columns were added by
        // 2026_02_16_222850_add_fields_to_members_table, and rolling back this
        // (usually no-op) migration must not drop them.
    }
};

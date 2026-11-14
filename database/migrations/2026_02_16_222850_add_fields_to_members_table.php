<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * This migration is dated before the tables it alters: `members` is created by
 * 2026_02_21_194256_create_members_table and `professional_history` (which the
 * employment columns are placed after) by 2026_02_23_231243_add_wizard_columns_to_members_table.
 *
 * On a fresh database there is nothing to alter yet, so it does nothing; the same
 * columns are then added by 2026_11_14_090000_add_missing_fields_to_members_table.
 * Databases that already ran this migration are unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('members') || ! Schema::hasColumn('members', 'professional_history')) {
            return;
        }

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

        // Drop the old spouse_name column (replaced by the spouse name parts above)
        if (Schema::hasColumn('members', 'spouse_name')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('spouse_name');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('members')) {
            return;
        }

        $columns = array_values(array_filter([
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
        ], fn (string $column) => Schema::hasColumn('members', $column)));

        if ($columns !== []) {
            Schema::table('members', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};

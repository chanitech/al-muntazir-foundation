<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 2026_02_16_222850_add_fields_to_members_table replaced members.spouse_name with
 * spouse_first_name / spouse_middle_name / spouse_surname and dropped it. On a fresh
 * install that migration runs before `members` exists and skips, so the old column
 * stayed behind. Drop it here so fresh installs match existing databases.
 *
 * Does nothing where the column is already gone (production), and keeps it if it
 * holds any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('members', 'spouse_name')
            || DB::table('members')->whereNotNull('spouse_name')->exists()) {
            return;
        }

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('spouse_name');
        });
    }

    public function down(): void
    {
        // Nothing to restore: the column was unused and empty.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The event forms offer a "Draft" status, but the original column only allowed
 * upcoming / ongoing / completed / cancelled, so saving a draft failed on MySQL.
 * The column becomes a plain string; existing values are kept.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('events', 'status')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            $table->string('status', 20)->default('upcoming')->change();
        });
    }

    public function down(): void
    {
        // Not reversed: turning the column back into an enum would fail for
        // events saved as drafts.
    }
};

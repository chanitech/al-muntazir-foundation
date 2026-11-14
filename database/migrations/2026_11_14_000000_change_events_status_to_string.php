<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * events.status was enum('upcoming','ongoing','completed','cancelled'), so
 * saving a 'draft' event failed on MySQL strict mode ("Data truncated").
 *
 * Turning it into a plain string column keeps every existing value as-is
 * (MySQL copies each enum value as its text) and keeps the same NOT NULL
 * and 'upcoming' default. Allowed values are now enforced by
 * App\Models\Event::STATUSES in the controller validation.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('status')->default('upcoming')->change();
        });
    }

    public function down(): void
    {
        // The old enum cannot hold 'draft'. Refuse to roll back rather than
        // silently changing those events' status or failing halfway.
        $drafts = DB::table('events')->where('status', 'draft')->count();

        if ($drafts > 0) {
            throw new RuntimeException(
                "Cannot roll back: {$drafts} event(s) have status 'draft', which the old enum column does not allow. "
                ."Change their status first."
            );
        }

        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])
                ->default('upcoming')
                ->change();
        });
    }
};

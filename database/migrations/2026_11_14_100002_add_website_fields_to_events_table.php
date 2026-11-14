<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets an event be published on the public website (off by default, so
 * internal member events stay private) and gives it an optional cover image.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        if (! Schema::hasColumn('events', 'is_public')) {
            Schema::table('events', fn (Blueprint $table) => $table->boolean('is_public')->default(false));
        }

        if (! Schema::hasColumn('events', 'image')) {
            Schema::table('events', fn (Blueprint $table) => $table->string('image')->nullable());
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('events', 'image')) {
            Schema::table('events', fn (Blueprint $table) => $table->dropColumn('image'));
        }
    }
};

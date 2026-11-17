<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('events', 'sms_sent')) {
            Schema::table('events', fn (Blueprint $table) => $table->boolean('sms_sent')->default(false));
        }

        if (! Schema::hasColumn('events', 'sms_sent_at')) {
            Schema::table('events', fn (Blueprint $table) => $table->timestamp('sms_sent_at')->nullable());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};

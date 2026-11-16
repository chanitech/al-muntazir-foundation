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
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('vote_audit_logs')) {
            return;
        }

        Schema::create('vote_audit_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('member_id')->nullable();
    $table->string('action');
    $table->text('description')->nullable();
    $table->ipAddress('ip_address')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_audit_logs');
    }
};

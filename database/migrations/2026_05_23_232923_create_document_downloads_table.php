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
        // Also defined (as an id/timestamps placeholder, now a no-op) by
        // 2026_02_23_232822_create_document_downloads_table.
        if (Schema::hasTable('document_downloads')) {
            return;
        }

        Schema::create('document_downloads', function (Blueprint $table) {

            $table->id();

            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();

            $table->foreignId('member_id')
                ->nullable()
                ->constrained('members')
                ->nullOnDelete();

            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['document_id']);
            $table->index(['member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_downloads');
    }
};

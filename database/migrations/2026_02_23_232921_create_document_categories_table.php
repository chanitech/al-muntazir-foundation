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
        // 2026_02_23_232822_create_document_categories_table.
        if (Schema::hasTable('document_categories')) {
            return;
        }

        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['slug']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};

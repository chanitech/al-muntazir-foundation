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
        // On a fresh database `documents` is created earlier, with its full schema, by
        // 2026_02_23_232921_fix_documents_table_schema (document_downloads, dated
        // 2026_05_23, already references it).
        if (Schema::hasTable('documents')) {
            return;
        }

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally empty: the table is dropped by
        // 2026_02_23_232921_fix_documents_table_schema. Dropping it here would fail
        // while document_downloads still references it.
    }
};

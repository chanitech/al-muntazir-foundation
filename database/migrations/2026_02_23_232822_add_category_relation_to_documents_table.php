<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dated before the documents table is created: on a fresh install the
        // column is created together with the table instead.
        if (! Schema::hasTable('documents') || ! Schema::hasTable('document_categories')) {
            return;
        }

        if (! Schema::hasColumn('documents', 'document_category_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->foreignId('document_category_id')
                      ->nullable()
                      ->constrained('document_categories')
                      ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn('document_category_id');

        });
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Dated before `documents` and `document_categories` are created. On a fresh database
 * neither table exists yet, so this does nothing: 2026_02_23_232921_fix_documents_table_schema
 * then creates `documents` with this column.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('documents')
            || ! Schema::hasTable('document_categories')
            || Schema::hasColumn('documents', 'document_category_id')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('document_category_id')
                  ->after('id')
                  ->constrained('document_categories')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('documents') || ! Schema::hasColumn('documents', 'document_category_id')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('document_category_id');
        });
    }
};

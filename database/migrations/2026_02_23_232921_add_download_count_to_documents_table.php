<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Runs before `documents` exists on a fresh database (and before
 * 2026_02_23_232921_fix_documents_table_schema, which adds `is_public`). In that case it
 * does nothing: fix_documents_table_schema creates the table with this column.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('documents') || Schema::hasColumn('documents', 'download_count')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table) {
            $column = $table->unsignedBigInteger('download_count')->default(0);

            if (Schema::hasColumn('documents', 'is_public')) {
                $column->after('is_public');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('documents') || ! Schema::hasColumn('documents', 'download_count')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('download_count');
        });
    }
};

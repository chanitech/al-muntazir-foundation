<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * `is_active` is already part of 2026_02_23_232921_create_document_categories_table, so on
 * a fresh database this does nothing. It only adds the column to an older copy of the table
 * that lacks it.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('document_categories') || Schema::hasColumn('document_categories', 'is_active')) {
            return;
        }

        Schema::table('document_categories', function (Blueprint $table) {
            $table->boolean('is_active')
                  ->default(true)
                  ->after('id');
        });
    }

    public function down(): void
    {
        // Intentionally empty: the column normally comes from
        // 2026_02_23_232921_create_document_categories_table, which drops the table.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;

/*
 * Superseded by 2026_02_23_232921_create_document_categories_table, which creates
 * `document_categories` with its full schema. This early placeholder only created an id and
 * timestamps; running it on a fresh database would make that later migration a no-op
 * and leave the table without its real columns.
 *
 * Kept as a no-op (not deleted or renamed) because existing databases have it recorded
 * in their migrations table.
 */
return new class extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        // The table is dropped by 2026_02_23_232921_create_document_categories_table.
    }
};

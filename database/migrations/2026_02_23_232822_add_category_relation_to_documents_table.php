<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            if (!Schema::hasColumn('documents','document_category_id')) {

                $table->foreignId('document_category_id')
                      ->after('id')
                      ->constrained('document_categories')
                      ->cascadeOnDelete();

            }

        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn('document_category_id');

        });
    }
};
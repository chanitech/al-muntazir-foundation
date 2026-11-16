<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On a fresh install the documents table is created later, complete.
        if (! Schema::hasTable('documents')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table) {

            if (!Schema::hasColumn('documents','title')) {
                $table->string('title')->after('id');
            }

            if (!Schema::hasColumn('documents','description')) {
                $table->text('description')->nullable();
            }

            if (!Schema::hasColumn('documents','file_path')) {
                $table->string('file_path');
            }

            if (!Schema::hasColumn('documents','file_name')) {
                $table->string('file_name');
            }

            if (!Schema::hasColumn('documents','file_extension')) {
                $table->string('file_extension',20);
            }

            if (!Schema::hasColumn('documents','file_size')) {
                $table->integer('file_size');
            }

            if (!Schema::hasColumn('documents','is_public')) {
                $table->boolean('is_public')->default(false);
            }

            if (!Schema::hasColumn('documents','uploaded_by')) {
                $table->foreignId('uploaded_by')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Fresh database: `documents` would only be created later by
         * 2026_05_28_232822_create_documents_table, but document_downloads (2026_05_23)
         * already references it, and the columns of
         * 2026_02_23_232822_add_category_relation_to_documents_table and
         * 2026_02_23_232921_add_download_count_to_documents_table could not be added yet.
         * Create the table here, with the schema those migrations produce together, as
         * soon as `document_categories` exists.
         */
        if (! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();

                $table->foreignId('document_category_id')
                      ->constrained('document_categories')
                      ->cascadeOnDelete();

                $table->string('title');
                $table->text('description')->nullable();
                $table->string('file_path');
                $table->string('file_name');
                $table->string('file_extension', 20);
                $table->integer('file_size');
                $table->boolean('is_public')->default(false);
                $table->unsignedBigInteger('download_count')->default(0);

                $table->foreignId('uploaded_by')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();

                $table->timestamps();
            });

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

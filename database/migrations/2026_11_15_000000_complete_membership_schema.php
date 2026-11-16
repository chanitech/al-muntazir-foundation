<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Brings the membership tables to their complete shape on any database.
 *
 * Some older migrations are dated before the tables they change, so on a fresh
 * install they had to skip their work. This migration adds whatever is still
 * missing. On a database that already has everything, it changes nothing:
 * every step first checks whether the table or column already exists, and no
 * data is removed.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->completeMembers();
        $this->completeEventCategories();
        $this->completeDocumentCategories();
        $this->completeDocuments();
        $this->completeDocumentDownloads();
    }

    private function completeMembers(): void
    {
        $this->addMissing('members', [
            'first_name' => fn (Blueprint $t) => $t->string('first_name')->nullable(),
            'middle_name' => fn (Blueprint $t) => $t->string('middle_name')->nullable(),
            'last_name' => fn (Blueprint $t) => $t->string('last_name')->nullable(),
            'current_country' => fn (Blueprint $t) => $t->string('current_country')->nullable(),
            'current_region' => fn (Blueprint $t) => $t->string('current_region')->nullable(),
            'current_district' => fn (Blueprint $t) => $t->string('current_district')->nullable(),
            'current_village' => fn (Blueprint $t) => $t->string('current_village')->nullable(),
            'spouse_first_name' => fn (Blueprint $t) => $t->string('spouse_first_name')->nullable(),
            'spouse_middle_name' => fn (Blueprint $t) => $t->string('spouse_middle_name')->nullable(),
            'spouse_surname' => fn (Blueprint $t) => $t->string('spouse_surname')->nullable(),
            'is_employed' => fn (Blueprint $t) => $t->boolean('is_employed')->default(false),
            'employer_name' => fn (Blueprint $t) => $t->string('employer_name')->nullable(),
            'job_title' => fn (Blueprint $t) => $t->string('job_title')->nullable(),
            'employment_status' => fn (Blueprint $t) => $t->string('employment_status')->nullable(),
            'employer_address' => fn (Blueprint $t) => $t->text('employer_address')->nullable(),
            'academic_history' => fn (Blueprint $t) => $t->json('academic_history')->nullable(),
            'professional_history' => fn (Blueprint $t) => $t->json('professional_history')->nullable(),
            'family_info' => fn (Blueprint $t) => $t->json('family_info')->nullable(),
        ]);
    }

    private function completeEventCategories(): void
    {
        // Used by the event forms ("active categories") but never created by a migration.
        $this->addMissing('event_categories', [
            'is_active' => fn (Blueprint $t) => $t->boolean('is_active')->default(true),
        ]);
    }

    private function completeDocumentCategories(): void
    {
        $this->addMissing('document_categories', [
            'name' => fn (Blueprint $t) => $t->string('name')->nullable(),
            'slug' => fn (Blueprint $t) => $t->string('slug')->nullable()->unique(),
            'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
            'is_active' => fn (Blueprint $t) => $t->boolean('is_active')->default(true),
        ]);
    }

    private function completeDocuments(): void
    {
        $this->addMissing('documents', [
            'document_category_id' => fn (Blueprint $t) => $t->foreignId('document_category_id')->nullable()->constrained('document_categories')->cascadeOnDelete(),
            'title' => fn (Blueprint $t) => $t->string('title')->nullable(),
            'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
            'file_path' => fn (Blueprint $t) => $t->string('file_path')->nullable(),
            'file_name' => fn (Blueprint $t) => $t->string('file_name')->nullable(),
            'file_extension' => fn (Blueprint $t) => $t->string('file_extension', 20)->nullable(),
            'file_size' => fn (Blueprint $t) => $t->integer('file_size')->nullable(),
            'is_public' => fn (Blueprint $t) => $t->boolean('is_public')->default(false),
            'download_count' => fn (Blueprint $t) => $t->unsignedBigInteger('download_count')->default(0),
            'uploaded_by' => fn (Blueprint $t) => $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete(),
        ]);
    }

    private function completeDocumentDownloads(): void
    {
        if (! Schema::hasTable('document_downloads')) {
            Schema::create('document_downloads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
                $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
                $table->ipAddress('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();
                $table->index(['document_id']);
                $table->index(['member_id']);
            });

            return;
        }

        $this->addMissing('document_downloads', [
            'document_id' => fn (Blueprint $t) => $t->foreignId('document_id')->nullable()->constrained('documents')->cascadeOnDelete(),
            'member_id' => fn (Blueprint $t) => $t->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete(),
            'ip_address' => fn (Blueprint $t) => $t->ipAddress('ip_address')->nullable(),
            'user_agent' => fn (Blueprint $t) => $t->string('user_agent')->nullable(),
        ]);
    }

    /** @param array<string, callable(Blueprint): mixed> $columns */
    private function addMissing(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $name => $define) {
            if (! Schema::hasColumn($table, $name)) {
                Schema::table($table, fn (Blueprint $blueprint) => $define($blueprint));
            }
        }
    }

    public function down(): void
    {
        // Nothing to undo safely: the columns may have existed before this migration.
    }
};

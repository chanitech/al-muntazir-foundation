<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('member_academic_backgrounds')) {
            return;
        }

        Schema::create('member_academic_backgrounds', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */
            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Academic Information (Dynamic Friendly)
            |--------------------------------------------------------------------------
            */
            $table->string('institution_name');
            $table->string('education_level');

            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();

            /*
            |--------------------------------------------------------------------------
            | File Upload Support
            |--------------------------------------------------------------------------
            */
            $table->string('certificate_file')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Production Safety
            |--------------------------------------------------------------------------
            */
            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexing
            |--------------------------------------------------------------------------
            */
            $table->index('member_id');
            $table->index('education_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_academic_backgrounds');
    }
};
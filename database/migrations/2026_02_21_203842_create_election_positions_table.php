<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_positions', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */
            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Position Control Inside Election
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_open')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit Safety
            |--------------------------------------------------------------------------
            */
            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Integrity Protection
            |--------------------------------------------------------------------------
            */
            $table->unique(['election_id', 'position_id']);

            /*
            |--------------------------------------------------------------------------
            | Performance Indexing
            |--------------------------------------------------------------------------
            */
            $table->index('is_open');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_positions');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Candidate Relationship
            |--------------------------------------------------------------------------
            */
            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Election Context
            |--------------------------------------------------------------------------
            */
            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Candidate Info
            |--------------------------------------------------------------------------
            */
            $table->text('manifesto')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Future Proofing (Very Important 🔥)
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_approved')->default(false);

            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexing
            |--------------------------------------------------------------------------
            */
            $table->index(['member_id']);
            $table->index(['election_id']);
            $table->index(['position_id']);
            $table->index(['is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
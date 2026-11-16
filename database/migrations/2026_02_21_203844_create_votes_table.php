<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('votes')) {
            return;
        }

        Schema::create('votes', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Voting Relationships
            |--------------------------------------------------------------------------
            */
            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnDelete();

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Security Tracking
            |--------------------------------------------------------------------------
            */
            $table->string('ip_address')->nullable();
            $table->string('device_hash')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Vote Validation Status
            |--------------------------------------------------------------------------
            */
            $table->string('vote_status')->default('valid');

            /*
            |--------------------------------------------------------------------------
            | Audit Safety
            |--------------------------------------------------------------------------
            */
            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Integrity Constraint
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'member_id',
                'election_id',
                'position_id'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Performance Indexing
            |--------------------------------------------------------------------------
            */
            $table->index('candidate_id');
            $table->index('vote_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('members')) {
            return;
        }

        Schema::create('members', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Authentication Link
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Membership Information
            |--------------------------------------------------------------------------
            */
            $table->string('membership_number')->unique();
            $table->date('join_date');

            $table->foreignId('membership_type_id')
                ->constrained('membership_types')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Status Control (Dynamic Friendly)
            |--------------------------------------------------------------------------
            */
            $table->string('status')->default('Active');

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('tribe')->nullable();

            $table->string('national_id')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();

            $table->text('address')->nullable();
            $table->string('photo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Marital Information
            |--------------------------------------------------------------------------
            */
            $table->foreignId('marital_status_id')
                ->nullable()
                ->constrained('marital_statuses')
                ->nullOnDelete();

            $table->string('spouse_name')->nullable();
            $table->string('spouse_phone')->nullable();

            $table->integer('children_count')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Soft Delete Support
            |--------------------------------------------------------------------------
            */
            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexing (Performance Optimization)
            |--------------------------------------------------------------------------
            */
            $table->index('membership_number');
            $table->index('phone');
            $table->index('national_id');
            $table->index('membership_type_id');
            $table->index('marital_status_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
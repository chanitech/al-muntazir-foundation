<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('elections')) {
            return;
        }

        Schema::create('elections', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Election Timing Control
            |--------------------------------------------------------------------------
            */
            $table->timestamp('start_time');
            $table->timestamp('end_time');

            /*
            |--------------------------------------------------------------------------
            | Status Control (Dynamic Friendly)
            |--------------------------------------------------------------------------
            | Instead of forcing boolean logic only, system can evolve later.
            */
            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Production Safety
            |--------------------------------------------------------------------------
            */
            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Performance Optimization
            |--------------------------------------------------------------------------
            */
            $table->index(['start_time', 'end_time']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
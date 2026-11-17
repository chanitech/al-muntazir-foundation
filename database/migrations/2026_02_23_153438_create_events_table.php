<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('events')) {
            return;
        }

        Schema::create('events', function (Blueprint $table) {
    $table->id();

    $table->foreignId('event_category_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();

    $table->date('event_date');
    $table->time('start_time')->nullable();
    $table->time('end_time')->nullable();

    $table->string('location')->nullable();
    $table->integer('capacity')->nullable();

    $table->enum('status',['upcoming','ongoing','completed','cancelled'])
          ->default('upcoming');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

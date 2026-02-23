<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->text('description')->nullable();

            // Dynamic system control
            $table->boolean('is_active')->default(true);

            // Production best practice
            $table->softDeletes();

            $table->timestamps();

            // Performance optimization
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};
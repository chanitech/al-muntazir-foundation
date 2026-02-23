<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            // Dynamic control
            $table->boolean('is_active')->default(true);

            // Production safety
            $table->softDeletes();

            $table->timestamps();

            // Performance optimization
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marital_statuses');
    }
};
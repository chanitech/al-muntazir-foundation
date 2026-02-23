<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {

            $table->id();

            $table->string('name')->unique();

            /*
            |--------------------------------------------------------------------------
            | Dynamic Control
            |--------------------------------------------------------------------------
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
            | Indexing
            |--------------------------------------------------------------------------
            */
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
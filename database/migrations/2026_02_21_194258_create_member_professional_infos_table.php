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
        if (Schema::hasTable('member_professional_infos')) {
            return;
        }

        Schema::create('member_professional_infos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained()->cascadeOnDelete();

    $table->string('occupation')->nullable();
    $table->string('employer')->nullable();
    $table->string('work_address')->nullable();
    $table->integer('years_of_experience')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_professional_infos');
    }
};

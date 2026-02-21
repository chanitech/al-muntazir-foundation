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
        Schema::create('member_family_infos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained()->cascadeOnDelete();

    $table->string('father_name')->nullable();
    $table->string('father_occupation')->nullable();
    $table->string('mother_name')->nullable();
    $table->string('mother_occupation')->nullable();
    $table->string('guardian_name')->nullable();
    $table->string('guardian_occupation')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_family_infos');
    }
};

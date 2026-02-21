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
        Schema::create('members', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();

    // Membership
    $table->string('membership_number')->unique();
    $table->date('join_date');
    $table->foreignId('membership_type_id')->constrained();

    $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');

    // Personal Info
    $table->string('full_name');
    $table->date('date_of_birth')->nullable();
    $table->string('place_of_birth')->nullable();
    $table->string('nationality')->nullable();
    $table->string('religion')->nullable();
    $table->string('tribe')->nullable();
    $table->string('national_id')->nullable()->unique();
    $table->string('phone')->unique();
    $table->string('email')->nullable();
    $table->text('address')->nullable();
    $table->string('photo')->nullable();

    $table->foreignId('marital_status_id')->nullable()->constrained();

    $table->string('spouse_name')->nullable();
    $table->string('spouse_phone')->nullable();
    $table->integer('children_count')->default(0);

    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

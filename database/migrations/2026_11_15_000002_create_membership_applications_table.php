<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membership applications sent from the public website. An administrator
 * reviews each one and approves it (which creates the member) or rejects it.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('membership_applications')) {
            return;
        }

        Schema::create('membership_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('national_id', 50)->nullable();
            $table->foreignId('membership_type_id')->nullable()->constrained('membership_types')->nullOnDelete();
            $table->string('current_region')->nullable();
            $table->string('current_district')->nullable();
            $table->string('current_village')->nullable();
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->text('motivation')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('review_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_applications');
    }
};

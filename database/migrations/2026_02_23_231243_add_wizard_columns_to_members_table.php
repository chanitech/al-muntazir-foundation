<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {

            $table->json('academic_history')->nullable()->after('photo');

            $table->json('professional_history')->nullable()->after('academic_history');

            $table->json('family_info')->nullable()->after('professional_history');

        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {

            $table->dropColumn([
                'academic_history',
                'professional_history',
                'family_info'
            ]);

        });
    }
};
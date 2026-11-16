<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['academic_history', 'professional_history', 'family_info'] as $column) {
            if (! Schema::hasColumn('members', $column)) {
                Schema::table('members', fn (Blueprint $table) => $table->json($column)->nullable());
            }
        }
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
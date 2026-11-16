<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        if (Schema::hasTable('academic_institutions') && ! Schema::hasColumn('academic_institutions', 'deleted_at')) {
            Schema::table('academic_institutions', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::table('academic_institutions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
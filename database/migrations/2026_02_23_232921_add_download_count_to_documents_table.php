<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('documents') && ! Schema::hasColumn('documents', 'download_count')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedBigInteger('download_count')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('download_count');
        });
    }
};
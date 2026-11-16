<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['first_name', 'middle_name', 'last_name'] as $column) {
            if (! Schema::hasColumn('members', $column)) {
                Schema::table('members', fn (Blueprint $table) => $table->string($column)->nullable());
            }
        }
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
        });
    }
};
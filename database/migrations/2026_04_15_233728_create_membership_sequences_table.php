<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Already created (e.g. this migration was renamed after it ran): nothing to do.
        if (Schema::hasTable('membership_sequences')) {
            return;
        }

        Schema::create('membership_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });

        // Insert the initial row
        DB::table('membership_sequences')->insert(['last_number' => 0]);
    }

    public function down()
    {
        Schema::dropIfExists('membership_sequences');
    }
};
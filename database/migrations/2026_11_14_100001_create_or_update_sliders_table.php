<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Homepage slider. The live server may already have a `sliders` table,
 * so this migration creates it when missing, or otherwise only adds the
 * columns that are not there yet (existing data is never touched).
 */
return new class extends Migration
{
    private array $columns = [
        'title' => ['string', true],
        'subtitle' => ['text', true],
        'image' => ['string', true],
        'button_text' => ['string', true],
        'button_link' => ['string', true],
        'sort_order' => ['unsignedInteger', false, 0],
        'is_active' => ['boolean', false, true],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('sliders')) {
            Schema::create('sliders', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('subtitle')->nullable();
                $table->string('image')->nullable();
                $table->string('button_text')->nullable();
                $table->string('button_link')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            return;
        }

        foreach ($this->columns as $name => $definition) {
            if (Schema::hasColumn('sliders', $name)) {
                continue;
            }

            Schema::table('sliders', function (Blueprint $table) use ($name, $definition) {
                [$type, $nullable] = $definition;
                $column = $table->{$type}($name);

                if ($nullable) {
                    $column->nullable();
                } else {
                    $column->default($definition[2]);
                }
            });
        }

        if (! Schema::hasColumn('sliders', 'created_at')) {
            Schema::table('sliders', fn (Blueprint $table) => $table->timestamps());
        }
    }

    public function down(): void
    {
        // Intentionally left blank: the table may pre-date this migration.
    }
};

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
        Schema::create('lesson_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('lesson_duration')->default(45);
            $table->time('first_lesson_start')->default('07:00');
            $table->integer('break_after_lesson')->default(4);
            $table->integer('break_duration')->default(15);
            $table->integer('break2_after_lesson')->nullable();
            $table->integer('break2_duration')->nullable();
            $table->integer('total_lessons')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_settings');
    }
};

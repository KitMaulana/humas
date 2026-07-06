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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('status')->comment('PNS, Honorer, dsb');
            $table->string('education')->nullable()->comment('S1, S2, dsb');
            $table->year('teaching_since')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('student_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->string('academic_year');
            $table->integer('male_count')->default(0);
            $table->integer('female_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_stats');
        Schema::dropIfExists('staffs');
        Schema::dropIfExists('teachers');
    }
};

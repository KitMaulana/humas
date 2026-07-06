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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->comment('Siswa, Guru, Sekolah');
            $table->string('level')->nullable()->comment('Nasional, Provinsi, dsb');
            $table->year('year');
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('partner_type')->nullable();
            $table->timestamps();
        });

        Schema::create('alumni_stats', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->integer('college_count')->default(0);
            $table->integer('work_count')->default(0);
            $table->integer('entrepreneur_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_stats');
        Schema::dropIfExists('partnerships');
        Schema::dropIfExists('achievements');
    }
};

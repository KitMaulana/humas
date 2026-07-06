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
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('grade')->comment('X, XI, XII');
            $table->string('name')->comment('X-1, XI-IPA-1, dsb');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('SMAN 1 Ciruas');
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('history')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('google_maps_iframe')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->integer('sort_order')->default(0);
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->comment('Ruang Kelas, Lab, Perpustakaan, dsb');
            $table->integer('count')->default(1);
            $table->string('condition')->default('Baik');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('organization_structures');
        Schema::dropIfExists('school_profiles');
        Schema::dropIfExists('school_classes');
    }
};

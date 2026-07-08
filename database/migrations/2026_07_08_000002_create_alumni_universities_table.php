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
        Schema::create('alumni_universities', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('name');
            $table->string('category')->comment('ptn, ptn-lokal, pts');
            $table->integer('count')->default(0);
            $table->string('icon')->default('🏫');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_universities');
    }
};

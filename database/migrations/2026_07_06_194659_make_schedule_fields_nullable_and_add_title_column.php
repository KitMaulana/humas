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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['school_class_id']);
            $table->dropForeign(['subject_id']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('school_class_id')->nullable()->change();
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
            $table->unsignedBigInteger('subject_id')->nullable()->change();
            $table->string('title')->nullable()->after('subject_id');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('school_class_id')->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['school_class_id']);
            $table->dropForeign(['subject_id']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            \DB::table('schedules')->whereNull('school_class_id')->delete();
            \DB::table('schedules')->whereNull('teacher_id')->delete();
            \DB::table('schedules')->whereNull('subject_id')->delete();

            $table->unsignedBigInteger('school_class_id')->nullable(false)->change();
            $table->unsignedBigInteger('teacher_id')->nullable(false)->change();
            $table->unsignedBigInteger('subject_id')->nullable(false)->change();
            $table->dropColumn('title');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('school_class_id')->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
        });
    }
};

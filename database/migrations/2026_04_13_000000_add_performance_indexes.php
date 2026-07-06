<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add indexes to improve query performance on frequently filtered columns.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->index('day');
            $table->index(['day', 'start_time', 'end_time']);
            $table->index('teacher_id');
        });

        Schema::table('student_stats', function (Blueprint $table) {
            $table->index('academic_year');
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->index('year');
        });

        Schema::table('alumni_stats', function (Blueprint $table) {
            $table->index('year');
        });

        Schema::table('school_classes', function (Blueprint $table) {
            $table->index('grade');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['day']);
            $table->dropIndex(['day', 'start_time', 'end_time']);
            $table->dropIndex(['teacher_id']);
        });

        Schema::table('student_stats', function (Blueprint $table) {
            $table->dropIndex(['academic_year']);
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->dropIndex(['year']);
        });

        Schema::table('alumni_stats', function (Blueprint $table) {
            $table->dropIndex(['year']);
        });

        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropIndex(['grade']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};

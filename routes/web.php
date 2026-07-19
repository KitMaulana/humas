<?php

use App\Http\Controllers\WebController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\AlumniStatController;
use App\Http\Controllers\Admin\AlumniUniversityController;
use App\Http\Controllers\Admin\StudentStatController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\OrganizationStructureController;
use App\Http\Controllers\Admin\PartnershipController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\LessonSettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ─────────────────────────────────
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/statistik', [WebController::class, 'statistics'])->name('statistics');
Route::get('/jadwal', [WebController::class, 'schedule'])->name('schedule');
Route::get('/prestasi', [WebController::class, 'achievements'])->name('achievements');
Route::get('/fasilitas', [WebController::class, 'facilities'])->name('facilities');
Route::get('/alumni', [WebController::class, 'alumni'])->name('alumni');

// ── Admin Panel Routes ────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (guest)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Protected routes
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Pengaturan Jam Pelajaran
        Route::get('lesson-settings', [LessonSettingController::class, 'edit'])->name('lesson-settings.edit');
        Route::put('lesson-settings', [LessonSettingController::class, 'update'])->name('lesson-settings.update');

        // CSV Import Routes (MUST be before resource routes)
        Route::get('schedules/import', [ScheduleController::class, 'showImport'])->name('schedules.import');
        Route::post('schedules/import', [ScheduleController::class, 'importCsv'])->name('schedules.import.process');
        Route::get('schedules/template', [ScheduleController::class, 'downloadTemplate'])->name('schedules.template');
        Route::get('teachers/import', [TeacherController::class, 'showImport'])->name('teachers.import');
        Route::post('teachers/import', [TeacherController::class, 'importCsv'])->name('teachers.import.process');
        Route::get('subjects/import', [SubjectController::class, 'showImport'])->name('subjects.import');
        Route::post('subjects/import', [SubjectController::class, 'importCsv'])->name('subjects.import.process');
        Route::get('school-classes/import', [SchoolClassController::class, 'showImport'])->name('school-classes.import');
        Route::post('school-classes/import', [SchoolClassController::class, 'importCsv'])->name('school-classes.import.process');

        // Data Akademik
        Route::resource('users', UserController::class);
        Route::resource('school-classes', SchoolClassController::class);
        Route::post('teachers/bulk-delete', [TeacherController::class, 'bulkDestroy'])->name('teachers.bulk-delete');
        Route::resource('teachers', TeacherController::class);
        Route::resource('staff', StaffController::class);
        Route::resource('subjects', SubjectController::class);
        Route::post('schedules/bulk-delete', [ScheduleController::class, 'bulkDestroy'])->name('schedules.bulk-delete');
        Route::resource('schedules', ScheduleController::class);

        // Data & Statistik
        Route::resource('achievements', AchievementController::class);
        Route::get('alumni-universities/import', [AlumniUniversityController::class, 'showImport'])->name('alumni-universities.import');
        Route::post('alumni-universities/import', [AlumniUniversityController::class, 'importCsv'])->name('alumni-universities.import.process');
        Route::get('alumni-universities/template', [AlumniUniversityController::class, 'downloadTemplate'])->name('alumni-universities.template');
        Route::resource('alumni-universities', AlumniUniversityController::class);
        Route::resource('alumni-stats', AlumniStatController::class);
        Route::resource('student-stats', StudentStatController::class);

        // Informasi Sekolah
        Route::resource('facilities', FacilityController::class);
        Route::resource('organization-structures', OrganizationStructureController::class);
        Route::resource('partnerships', PartnershipController::class);

        // Profil Sekolah (single record — edit & update only)
        Route::get('school-profile', [SchoolProfileController::class, 'edit'])->name('school-profile.edit');
        Route::put('school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');
    });
});

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\AlumniStat;
use App\Models\Facility;
use App\Models\SchoolClass;
use App\Models\Schedule;
use App\Models\StudentStat;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\Partnership;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Guru & Staf
        $teacherCount = Teacher::count();
        $staffCount = Staff::count();
        $pnsCount = Teacher::where('status', 'PNS')->count();
        $honorerCount = Teacher::where('status', 'like', '%Honorer%')->orWhere('status', 'like', '%Non%PNS%')->count();
        $otherTeacherStatusCount = max(0, $teacherCount - $pnsCount - $honorerCount);

        // 2. Data Siswa
        $maleStudentCount = StudentStat::sum('male_count');
        $femaleStudentCount = StudentStat::sum('female_count');
        $totalStudentCount = $maleStudentCount + $femaleStudentCount;
        $classCount = SchoolClass::count();

        // 3. Data Prestasi & Kemitraan
        $achievementCount = Achievement::count();
        $nationalAchievementCount = Achievement::where('level', 'like', '%Nasional%')->count();
        $provincialAchievementCount = Achievement::where('level', 'like', '%Provinsi%')->count();
        $otherAchievementCount = max(0, $achievementCount - $nationalAchievementCount - $provincialAchievementCount);
        $partnershipCount = Partnership::count();

        // 4. Data Pendukung Lainnya
        $subjectCount = Subject::count();
        $scheduleCount = Schedule::count();
        $facilityCount = Facility::count();
        $orgStructureCount = \App\Models\OrganizationStructure::count();

        return view('admin.dashboard', compact(
            'teacherCount', 'staffCount', 'pnsCount', 'honorerCount', 'otherTeacherStatusCount',
            'maleStudentCount', 'femaleStudentCount', 'totalStudentCount', 'classCount',
            'achievementCount', 'nationalAchievementCount', 'provincialAchievementCount', 'otherAchievementCount', 'partnershipCount',
            'subjectCount', 'scheduleCount', 'facilityCount', 'orgStructureCount'
        ));
    }
}

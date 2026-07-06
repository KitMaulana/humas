<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AlumniStat;
use App\Models\Facility;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Schedule;
use App\Models\StudentStat;
use App\Models\Teacher;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $profile = SchoolProfile::first();
        $teacherCount = Teacher::count();
        $studentCount = StudentStat::sum('male_count') + StudentStat::sum('female_count');
        $achievementCount = Achievement::count();
        $classCount = SchoolClass::count();

        // Lesson settings
        $lessonSetting = \App\Models\LessonSetting::current();
        $timeSlots = $lessonSetting->getTimeSlots();

        // KBM Grid: Get today's day
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $currentDay = $days[date('l')] ?? 'Senin';
        $currentTime = date('H:i:s');

        // Ongoing schedules (currently running KBM)
        $ongoingSchedules = Schedule::with(['schoolClass', 'teacher', 'subject'])
            ->where('day', $currentDay)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->orderBy('start_time')
            ->get();

        // Group ongoing schedules by grade X, XI, XII
        $schedulesByGrade = [
            'X' => [],
            'XI' => [],
            'XII' => [],
        ];

        foreach ($ongoingSchedules as $schedule) {
            if ($schedule->school_class_id === null) {
                $schedulesByGrade['X'][] = $schedule;
                $schedulesByGrade['XI'][] = $schedule;
                $schedulesByGrade['XII'][] = $schedule;
            } else {
                $grade = $schedule->schoolClass->grade ?? '';
                if (in_array($grade, ['X', 'XI', 'XII'])) {
                    $schedulesByGrade[$grade][] = $schedule;
                }
            }
        }

        return view('home', compact(
            'profile', 'teacherCount', 'studentCount', 'achievementCount',
            'classCount', 'ongoingSchedules', 'timeSlots', 'currentDay',
            'currentTime', 'schedulesByGrade', 'lessonSetting'
        ));
    }

    public function statistics(Request $request)
    {
        $classes = SchoolClass::all();
        $grades = SchoolClass::select('grade')->distinct()->pluck('grade');
        $academicYears = StudentStat::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
        
        $query = StudentStat::query()->with('schoolClass');
        
        // Filter by Academic Year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by Grade
        if ($request->filled('grade')) {
            $query->whereHas('schoolClass', function($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }
        
        // Filter by Class
        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }
        
        $stats = $query->get();
        
        // Process Gender Filter in aggregation
        $gender = $request->get('gender', 'all');
        
        // 1. Growth Chart Data (Last 5 years)
        $growthData = $stats->groupBy('academic_year')->map(function($yearGroup) use ($gender) {
            if ($gender == 'male') return $yearGroup->sum('male_count');
            if ($gender == 'female') return $yearGroup->sum('female_count');
            return $yearGroup->sum('male_count') + $yearGroup->sum('female_count');
        })->sortKeys();
        
        $growthLabels = $growthData->keys()->toArray();
        $growthValues = $growthData->values()->toArray();
        
        // 2. Major Distribution
        $majorGroups = [
            'MIPA' => 0,
            'IIS/IPS' => 0,
            'BAHASA' => 0,
            'LAINNYA' => 0
        ];
        
        foreach ($stats as $stat) {
            $className = strtoupper($stat->schoolClass->name);
            $count = ($gender == 'male') ? $stat->male_count : (($gender == 'female') ? $stat->female_count : ($stat->male_count + $stat->female_count));
            
            if (str_contains($className, 'MIPA') || str_contains($className, 'IPA')) {
                $majorGroups['MIPA'] += $count;
            } elseif (str_contains($className, 'IIS') || str_contains($className, 'IPS')) {
                $majorGroups['IIS/IPS'] += $count;
            } elseif (str_contains($className, 'BAHASA')) {
                $majorGroups['BAHASA'] += $count;
            } else {
                $majorGroups['LAINNYA'] += $count;
            }
        }
        
        $majorLabels = array_keys($majorGroups);
        $majorValues = array_values($majorGroups);
        
        $alumni = AlumniStat::all();
        
        return view('statistics', compact(
            'stats', 'alumni', 'classes', 'grades', 'academicYears',
            'growthLabels', 'growthValues', 'majorLabels', 'majorValues'
        ));
    }

    public function schedule(Request $request)
    {
        $classes = SchoolClass::orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $subjects = \App\Models\Subject::orderBy('name')->get();
        
        // Get ALL schedules with relationships for client-side filtering
        $allSchedules = Schedule::with(['schoolClass', 'teacher', 'subject'])
            ->orderBy('start_time')
            ->get();
        
        // Transform to JSON-friendly format grouped by day
        $schedulesJson = [];
        foreach ($allSchedules as $item) {
            $day = $item->day;
            if (!isset($schedulesJson[$day])) {
                $schedulesJson[$day] = [];
            }
            $schedulesJson[$day][] = [
                'id' => $item->id,
                'lesson_number' => $item->lesson_number,
                'start_time' => substr($item->start_time, 0, 5),
                'end_time' => substr($item->end_time, 0, 5),
                'subject_name' => $item->subject->name ?? '—',
                'subject_id' => $item->subject_id,
                'teacher_name' => $item->teacher->name ?? '—',
                'teacher_id' => $item->teacher_id,
                'class_name' => $item->schoolClass->name ?? '—',
                'class_id' => $item->school_class_id,
                'class_grade' => $item->schoolClass->grade ?? '',
                'title' => $item->title,
            ];
        }
        
        return view('schedule', compact('classes', 'teachers', 'subjects', 'schedulesJson'));
    }

    public function achievements()
    {
        $achievements = Achievement::orderBy('year', 'desc')->get();
        return view('achievements', compact('achievements'));
    }

    public function facilities()
    {
        $facilities = Facility::all();
        return view('facilities', compact('facilities'));
    }

    public function alumni()
    {
        $alumni = AlumniStat::orderBy('year', 'desc')->get();
        return view('alumni', compact('alumni'));
    }
}

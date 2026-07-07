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

        // Get all schedules for today
        $allTodaySchedules = Schedule::with(['schoolClass', 'teacher', 'subject'])
            ->where('day', $currentDay)
            ->get();

        // Process them (shifting logic & recess insertion)
        $processedTodaySchedules = $this->processSchedules($allTodaySchedules, $currentDay, $lessonSetting);

        // Filter currently running schedules (ongoing)
        $currentMin = substr($currentTime, 0, 5);
        $ongoingSchedules = collect($processedTodaySchedules)->filter(function($sch) use ($currentMin) {
            $start = substr($sch->start_time, 0, 5);
            $end = substr($sch->end_time, 0, 5);
            return $start <= $currentMin && $end >= $currentMin;
        });

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
        $allSchedules = Schedule::with(['schoolClass', 'teacher', 'subject'])->get();
        
        $lessonSetting = \App\Models\LessonSetting::current();
        
        // Transform to JSON-friendly format grouped by day
        $schedulesJson = [];
        $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        foreach ($daysList as $day) {
            $daySchedules = $allSchedules->filter(function($sch) use ($day) {
                return $sch->day === $day;
            });
            
            $processed = $this->processSchedules($daySchedules, $day, $lessonSetting);
            
            $schedulesJson[$day] = [];
            foreach ($processed as $item) {
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
                    'is_break' => $item->is_break ?? false,
                ];
            }
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

    private function processSchedules($schedules, $day, $lessonSetting)
    {
        // 1. Separate into global and regular
        $globalSchedules = $schedules->filter(function($sch) {
            return is_null($sch->school_class_id);
        });

        $regularSchedules = $schedules->filter(function($sch) {
            return !is_null($sch->school_class_id);
        });

        // Recalculate global schedules' times to match current settings
        foreach ($globalSchedules as $sch) {
            if ($sch->lesson_number) {
                $slot = $lessonSetting->getSlotTime($sch->lesson_number);
                if ($slot) {
                    $sch->start_time = $slot['start'];
                    $sch->end_time = $slot['end'];
                }
            }
        }

        // 2. Find global lesson numbers occupied on this day
        $globalLessonNumbers = $globalSchedules->pluck('lesson_number')->unique()->toArray();

        // 3. Generate JP mapping (original -> shifted)
        $mapping = [];
        $currentSlot = 1;
        for ($x = 1; $x <= 15; $x++) {
            while (in_array($currentSlot, $globalLessonNumbers)) {
                $currentSlot++;
            }
            $mapping[$x] = $currentSlot;
            $currentSlot++;
        }

        // 4. Shift regular schedules
        foreach ($regularSchedules as $sch) {
            $origJp = $sch->lesson_number;
            if ($origJp && isset($mapping[$origJp])) {
                $newJp = $mapping[$origJp];
                $sch->lesson_number = $newJp;
                $slot = $lessonSetting->getSlotTime($newJp);
                if ($slot) {
                    $sch->start_time = $slot['start'];
                    $sch->end_time = $slot['end'];
                }
            }
        }

        // 5. Build recesses
        $recesses = [];
        $slots = $lessonSetting->getTimeSlots();

        if ($lessonSetting->break_after_lesson && isset($slots[$lessonSetting->break_after_lesson]) && isset($slots[$lessonSetting->break_after_lesson + 1])) {
            $recess = new Schedule();
            $recess->id = 0;
            $recess->lesson_number = null;
            $recess->start_time = $slots[$lessonSetting->break_after_lesson]['end'];
            $recess->end_time = $slots[$lessonSetting->break_after_lesson + 1]['start'];
            $recess->title = 'Istirahat I';
            $recess->is_break = true;
            $recesses[] = $recess;
        }

        if ($lessonSetting->break2_after_lesson && isset($slots[$lessonSetting->break2_after_lesson]) && isset($slots[$lessonSetting->break2_after_lesson + 1])) {
            $recess = new Schedule();
            $recess->id = 0;
            $recess->lesson_number = null;
            $recess->start_time = $slots[$lessonSetting->break2_after_lesson]['end'];
            $recess->end_time = $slots[$lessonSetting->break2_after_lesson + 1]['start'];
            $recess->title = 'Istirahat II';
            $recess->is_break = true;
            $recesses[] = $recess;
        }

        // 6. Merge all
        $all = collect()
            ->concat($globalSchedules)
            ->concat($regularSchedules)
            ->concat($recesses);

        // 7. Sort by start_time
        return $all->sortBy('start_time')->values()->all();
    }
}

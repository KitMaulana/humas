<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonSetting extends Model
{
    protected $guarded = [];

    /**
     * Get or create the singleton settings record.
     */
    public static function current()
    {
        return static::firstOrCreate([], [
            'lesson_duration' => 45,
            'first_lesson_start' => '07:00',
            'break_after_lesson' => 4,
            'break_duration' => 15,
            'break2_after_lesson' => null,
            'break2_duration' => null,
            'total_lessons' => 10,
        ]);
    }

    /**
     * Calculate all time slots based on settings.
     * Returns array like: [1 => ['start' => '07:00', 'end' => '07:45'], 2 => ...]
     */
    public function getTimeSlots()
    {
        $slots = [];
        $currentMinutes = $this->timeToMinutes($this->first_lesson_start);
        $duration = $this->lesson_duration;

        for ($i = 1; $i <= $this->total_lessons; $i++) {
            $start = $this->minutesToTime($currentMinutes);
            $end = $this->minutesToTime($currentMinutes + $duration);
            $slots[$i] = [
                'number' => $i,
                'start' => $start,
                'end' => $end,
            ];
            $currentMinutes += $duration;

            // Add break after specified lesson
            if ($this->break_after_lesson && $i == $this->break_after_lesson) {
                $currentMinutes += $this->break_duration ?? 0;
            }
            if ($this->break2_after_lesson && $i == $this->break2_after_lesson) {
                $currentMinutes += $this->break2_duration ?? 0;
            }
        }

        return $slots;
    }

    /**
     * Get start_time and end_time for a specific lesson number.
     */
    public function getSlotTime($lessonNumber, $day = null)
    {
        if ($day) {
            $slots = $this->getTimeSlotsForDay($day);
            if ($slots && isset($slots[$lessonNumber])) {
                return $slots[$lessonNumber];
            }
        }
        $slots = $this->getTimeSlots();
        return $slots[$lessonNumber] ?? null;
    }

    /**
     * Get exact time slots for a specific day.
     */
    public function getTimeSlotsForDay($day)
    {
        $day = trim(strtolower($day));

        if ($day === 'senin') {
            return [
                1 => ['number' => 1, 'start' => '08:10', 'end' => '08:45'],
                2 => ['number' => 2, 'start' => '08:45', 'end' => '09:20'],
                3 => ['number' => 3, 'start' => '09:20', 'end' => '09:55'],
                4 => ['number' => 4, 'start' => '10:35', 'end' => '11:10'],
                5 => ['number' => 5, 'start' => '11:10', 'end' => '11:45'],
                6 => ['number' => 6, 'start' => '13:00', 'end' => '13:40'],
                7 => ['number' => 7, 'start' => '13:40', 'end' => '14:20'],
                8 => ['number' => 8, 'start' => '14:20', 'end' => '15:00'],
            ];
        } elseif ($day === 'jumat') {
            return [
                1 => ['number' => 1, 'start' => '08:20', 'end' => '09:00'],
                2 => ['number' => 2, 'start' => '09:00', 'end' => '09:40'],
                3 => ['number' => 3, 'start' => '10:20', 'end' => '11:00'],
                4 => ['number' => 4, 'start' => '11:00', 'end' => '11:40'],
            ];
        } else {
            // Selasa, Rabu, Kamis (and Saturday/Sunday fallback)
            return [
                1 => ['number' => 1, 'start' => '07:00', 'end' => '07:35'],
                2 => ['number' => 2, 'start' => '07:35', 'end' => '08:10'],
                3 => ['number' => 3, 'start' => '08:10', 'end' => '08:45'],
                4 => ['number' => 4, 'start' => '08:45', 'end' => '09:20'],
                5 => ['number' => 5, 'start' => '09:20', 'end' => '09:55'],
                6 => ['number' => 6, 'start' => '10:35', 'end' => '11:10'],
                7 => ['number' => 7, 'start' => '11:10', 'end' => '11:45'],
                8 => ['number' => 8, 'start' => '13:00', 'end' => '13:40'],
                9 => ['number' => 9, 'start' => '13:40', 'end' => '14:20'],
                10 => ['number' => 10, 'start' => '14:20', 'end' => '15:00'],
            ];
        }
    }

    /**
     * Get global activities/breaks (recesses) for a specific day.
     */
    public function getRecessesForDay($day)
    {
        $day = trim(strtolower($day));
        $recesses = [];

        if ($day === 'senin') {
            // 1. Upacara: 07:00 - 08:10
            $r1 = new Schedule();
            $r1->id = 0;
            $r1->lesson_number = null;
            $r1->start_time = '07:00:00';
            $r1->end_time = '08:10:00';
            $r1->title = 'UPACARA BENDERA / PEMBIASAAN';
            $r1->is_break = true;
            $recesses[] = $r1;

            // 2. Makan Bergizi Gratis (MBG): 09:55 - 10:35
            $r2 = new Schedule();
            $r2->id = 0;
            $r2->lesson_number = null;
            $r2->start_time = '09:55:00';
            $r2->end_time = '10:35:00';
            $r2->title = 'Makan Bergizi Gratis Bersama (MBG)';
            $r2->is_break = true;
            $recesses[] = $r2;

            // 3. Sholat Dhuhur Berjamaah: 11:45 - 13:00
            $r3 = new Schedule();
            $r3->id = 0;
            $r3->lesson_number = null;
            $r3->start_time = '11:45:00';
            $r3->end_time = '13:00:00';
            $r3->title = 'SHOLAT DHUHUR BERJAMAAH & ISTIRAHAT';
            $r3->is_break = true;
            $recesses[] = $r3;

        } elseif ($day === 'jumat') {
            // 1. Pembiasaan / Kajian Islami: 07:00 - 08:20
            $r1 = new Schedule();
            $r1->id = 0;
            $r1->lesson_number = null;
            $r1->start_time = '07:00:00';
            $r1->end_time = '08:20:00';
            $r1->title = 'PEMBIASAAN / KAJIAN ISLAMI';
            $r1->is_break = true;
            $recesses[] = $r1;

            // 2. Makan Bergizi Gratis (MBG): 09:40 - 10:20
            $r2 = new Schedule();
            $r2->id = 0;
            $r2->lesson_number = null;
            $r2->start_time = '09:40:00';
            $r2->end_time = '10:20:00';
            $r2->title = 'Makan Bergizi Gratis Bersama (MBG)';
            $r2->is_break = true;
            $recesses[] = $r2;

            // 3. Sholat Jumat Berjamaah: 11:55 - 13:00
            $r3 = new Schedule();
            $r3->id = 0;
            $r3->lesson_number = null;
            $r3->start_time = '11:55:00';
            $r3->end_time = '13:00:00';
            $r3->title = 'ISTIRAHAT (SHOLAT JUMAT BERJAMAAH)';
            $r3->is_break = true;
            $recesses[] = $r3;

            // 4. Ekstrakurikuler: 13:00 - 16:00
            $r4 = new Schedule();
            $r4->id = 0;
            $r4->lesson_number = null;
            $r4->start_time = '13:00:00';
            $r4->end_time = '16:00:00';
            $r4->title = 'EKSTRAKURIKULER';
            $r4->is_break = true;
            $recesses[] = $r4;

        } else {
            // Selasa, Rabu, Kamis
            // 1. Makan Bergizi Gratis (MBG): 09:55 - 10:35
            $r1 = new Schedule();
            $r1->id = 0;
            $r1->lesson_number = null;
            $r1->start_time = '09:55:00';
            $r1->end_time = '10:35:00';
            $r1->title = 'Makan Bergizi Gratis Bersama (MBG)';
            $r1->is_break = true;
            $recesses[] = $r1;

            // 2. Sholat Dhuhur Berjamaah: 11:45 - 13:00
            $r2 = new Schedule();
            $r2->id = 0;
            $r2->lesson_number = null;
            $r2->start_time = '11:45:00';
            $r2->end_time = '13:00:00';
            $r2->title = 'SHOLAT DHUHUR BERJAMAAH & ISTIRAHAT';
            $r2->is_break = true;
            $recesses[] = $r2;
        }

        return $recesses;
    }

    private function timeToMinutes($time)
    {
        $parts = explode(':', $time);
        return (int)$parts[0] * 60 + (int)$parts[1];
    }

    private function minutesToTime($minutes)
    {
        $h = str_pad(floor($minutes / 60), 2, '0', STR_PAD_LEFT);
        $m = str_pad($minutes % 60, 2, '0', STR_PAD_LEFT);
        return "{$h}:{$m}";
    }
}

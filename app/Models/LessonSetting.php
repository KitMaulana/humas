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
    public function getSlotTime($lessonNumber)
    {
        $slots = $this->getTimeSlots();
        return $slots[$lessonNumber] ?? null;
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Recalculate start_time and end_time from lesson_number using current LessonSettings.
     */
    public function recalculateTime()
    {
        if ($this->lesson_number) {
            $settings = LessonSetting::current();
            $slot = $settings->getSlotTime($this->lesson_number);
            if ($slot) {
                $this->start_time = $slot['start'];
                $this->end_time = $slot['end'];
            }
        }
    }
}

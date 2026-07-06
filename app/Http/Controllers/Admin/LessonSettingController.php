<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LessonSetting;
use App\Models\Schedule;
use Illuminate\Http\Request;

class LessonSettingController extends Controller
{
    public function edit()
    {
        $setting = LessonSetting::current();
        $timeSlots = $setting->getTimeSlots();
        return view('admin.lesson-settings.edit', compact('setting', 'timeSlots'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'lesson_duration' => 'required|integer|min:20|max:120',
            'first_lesson_start' => 'required|date_format:H:i',
            'break_after_lesson' => 'required|integer|min:1|max:10',
            'break_duration' => 'required|integer|min:5|max:60',
            'break2_after_lesson' => 'nullable|integer|min:1|max:10',
            'break2_duration' => 'nullable|integer|min:5|max:60',
            'total_lessons' => 'required|integer|min:1|max:15',
        ]);

        $setting = LessonSetting::current();
        $setting->update($validated);

        // Recalculate all schedule times that have lesson_number
        $schedules = Schedule::whereNotNull('lesson_number')->get();
        foreach ($schedules as $schedule) {
            $schedule->recalculateTime();
            $schedule->save();
        }

        return redirect()->route('admin.lesson-settings.edit')->with('success', 'Pengaturan jam pelajaran berhasil diperbarui. ' . $schedules->count() . ' jadwal telah dihitung ulang.');
    }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\LessonSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScheduleController extends Controller {
    public function index(Request $request) {
        $query = Schedule::query()->with(['schoolClass', 'teacher', 'subject']);
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }
        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        $perPage = $request->get('per_page', 15);
        if ($perPage === 'all' || $perPage === 'semua') {
            $paginateCount = 999999;
        } else {
            $paginateCount = in_array($perPage, [10, 20, 50, 100]) ? (int)$perPage : 15;
        }

        $items = $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                       ->orderBy('start_time')
                       ->paginate($paginateCount)->withQueryString();
        $classes = SchoolClass::orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->get();
        return view('admin.schedules.index', compact('items', 'classes'));
    }

    public function create() {
        $classes = SchoolClass::orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $setting = LessonSetting::current();
        $timeSlots = $setting->getTimeSlots();
        return view('admin.schedules.form', compact('classes', 'teachers', 'subjects', 'timeSlots'));
    }

    public function store(Request $request) {
        $isGlobal = $request->filled('title');

        $rules = [
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'lesson_number' => 'required|integer|min:1|max:15',
        ];

        if ($isGlobal) {
            $rules['title'] = 'required|string|max:255';
        } else {
            $rules['school_class_id'] = 'required|exists:school_classes,id';
            $rules['teacher_id'] = 'required|exists:teachers,id';
            $rules['subject_id'] = 'required|exists:subjects,id';
        }

        $validated = $request->validate($rules);

        if ($isGlobal) {
            $validated['school_class_id'] = null;
            $validated['teacher_id'] = null;
            $validated['subject_id'] = null;
        } else {
            $validated['title'] = null;
        }

        $setting = LessonSetting::current();
        $slot = $setting->getSlotTime($validated['lesson_number'], $validated['day']);
        $validated['start_time'] = $slot['start'] ?? '00:00';
        $validated['end_time'] = $slot['end'] ?? '00:00';

        Schedule::create($validated);
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule) {
        $item = $schedule;
        $classes = SchoolClass::orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $setting = LessonSetting::current();
        $timeSlots = $setting->getTimeSlots();
        return view('admin.schedules.form', compact('item', 'classes', 'teachers', 'subjects', 'timeSlots'));
    }

    public function update(Request $request, Schedule $schedule) {
        $isGlobal = $request->filled('title');

        $rules = [
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'lesson_number' => 'required|integer|min:1|max:15',
        ];

        if ($isGlobal) {
            $rules['title'] = 'required|string|max:255';
        } else {
            $rules['school_class_id'] = 'required|exists:school_classes,id';
            $rules['teacher_id'] = 'required|exists:teachers,id';
            $rules['subject_id'] = 'required|exists:subjects,id';
        }

        $validated = $request->validate($rules);

        if ($isGlobal) {
            $validated['school_class_id'] = null;
            $validated['teacher_id'] = null;
            $validated['subject_id'] = null;
        } else {
            $validated['title'] = null;
        }

        $setting = LessonSetting::current();
        $slot = $setting->getSlotTime($validated['lesson_number'], $validated['day']);
        $validated['start_time'] = $slot['start'] ?? '00:00';
        $validated['end_time'] = $slot['end'] ?? '00:00';

        $schedule->update($validated);
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule) {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    public function bulkDestroy(Request $request) {
        $ids = $request->input('ids', []);
        if (count($ids) > 0) {
            Schedule::whereIn('id', $ids)->delete();
            return redirect()->route('admin.schedules.index')->with('success', count($ids) . ' jadwal pelajaran berhasil dihapus secara massal.');
        }
        return redirect()->route('admin.schedules.index')->with('error', 'Tidak ada jadwal pelajaran yang dipilih.');
    }

    public function showImport() {
        return view('admin.schedules.import');
    }

    public function importCsv(Request $request) {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        // Normalize header names
        $header = array_map(function($h) {
            return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h)));
        }, $header);

        $required = ['hari', 'jam_ke', 'nama_kelas', 'nama_guru', 'mata_pelajaran'];
        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom '{$col}' tidak ditemukan di file CSV. Kolom wajib: " . implode(', ', $required));
            }
        }

        $setting = LessonSetting::current();
        $imported = 0;
        $errors = [];
        $lineNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNum++;
            if (count($row) < count($required)) continue;

            $data = array_combine($header, array_pad($row, count($header), ''));

            // Validate day
            $validDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            if (!in_array($data['hari'], $validDays)) {
                $errors[] = "Baris {$lineNum}: Hari '{$data['hari']}' tidak valid.";
                continue;
            }

            $lessonNumber = (int) $data['jam_ke'];
            $daySlots = $setting->getTimeSlotsForDay($data['hari']);
            if (!isset($daySlots[$lessonNumber])) {
                $errors[] = "Baris {$lineNum}: Jam ke-{$lessonNumber} tidak valid untuk hari {$data['hari']}.";
                continue;
            }

            // Find or create class
            $className = trim($data['nama_kelas']);
            $grade = $this->detectGrade($className);
            $class = SchoolClass::firstOrCreate(
                ['name' => $className],
                ['grade' => $grade, 'slug' => Str::slug($className)]
            );

            // Find or create teacher
            $teacher = Teacher::firstOrCreate(
                ['name' => trim($data['nama_guru'])],
                ['status' => 'Data Import']
            );

            // Find or create subject
            $subjectName = trim($data['mata_pelajaran']);
            $subject = Subject::firstOrCreate(
                ['name' => $subjectName],
                ['slug' => Str::slug($subjectName)]
            );

            // Calculate time from lesson number
            $slot = $setting->getSlotTime($lessonNumber, $data['hari']);

            Schedule::updateOrCreate(
                [
                    'school_class_id' => $class->id,
                    'day' => $data['hari'],
                    'lesson_number' => $lessonNumber,
                ],
                [
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'start_time' => $slot['start'] ?? '00:00',
                    'end_time' => $slot['end'] ?? '00:00',
                ]
            );
            $imported++;
        }
        fclose($handle);

        $msg = "{$imported} jadwal berhasil diimport.";
        if (!empty($errors)) {
            $msg .= ' ' . count($errors) . ' baris dilewati: ' . implode(' | ', array_slice($errors, 0, 5));
        }

        return redirect()->route('admin.schedules.index')->with('success', $msg);
    }

    public function downloadTemplate() {
        $path = public_path('Template_Jadwal.csv');
        return response()->download($path, 'Template_Jadwal.csv');
    }

    private function detectGrade($className) {
        $className = strtoupper($className);
        if (str_starts_with($className, 'XII')) return 'XII';
        if (str_starts_with($className, 'XI')) return 'XI';
        if (str_starts_with($className, 'X')) return 'X';
        return 'X';
    }
}

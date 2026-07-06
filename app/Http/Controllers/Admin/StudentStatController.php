<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\StudentStat;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class StudentStatController extends Controller {
    public function index(Request $request) {
        $query = StudentStat::query()->with('schoolClass');
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        $items = $query->orderBy('academic_year', 'desc')->paginate(15)->withQueryString();
        $years = StudentStat::select('academic_year')->distinct()->pluck('academic_year');
        return view('admin.student-stats.index', compact('items', 'years'));
    }
    public function create() {
        $classes = SchoolClass::orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->get();
        return view('admin.student-stats.form', compact('classes'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'academic_year' => 'required|string|max:50',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
        ]);
        $exists = StudentStat::where('school_class_id', $request->school_class_id)
                             ->where('academic_year', $request->academic_year)
                             ->exists();
        if ($exists) {
            return back()->withErrors(['school_class_id' => 'Data statistik kelas tersebut untuk tahun ajaran ini sudah diinput.'])->withInput();
        }
        StudentStat::create($validated);
        return redirect()->route('admin.student-stats.index')->with('success', 'Statistik siswa kelas berhasil ditambahkan.');
    }
    public function edit(StudentStat $studentStat) {
        $item = $studentStat;
        $classes = SchoolClass::orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->get();
        return view('admin.student-stats.form', compact('item', 'classes'));
    }
    public function update(Request $request, StudentStat $studentStat) {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'academic_year' => 'required|string|max:50',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
        ]);
        $exists = StudentStat::where('school_class_id', $request->school_class_id)
                             ->where('academic_year', $request->academic_year)
                             ->where('id', '!=', $studentStat->id)
                             ->exists();
        if ($exists) {
            return back()->withErrors(['school_class_id' => 'Data statistik kelas tersebut untuk tahun ajaran ini sudah diinput.'])->withInput();
        }
        $studentStat->update($validated);
        return redirect()->route('admin.student-stats.index')->with('success', 'Statistik siswa kelas berhasil diperbarui.');
    }
    public function destroy(StudentStat $studentStat) {
        $studentStat->delete();
        return redirect()->route('admin.student-stats.index')->with('success', 'Statistik siswa kelas berhasil dihapus.');
    }
}

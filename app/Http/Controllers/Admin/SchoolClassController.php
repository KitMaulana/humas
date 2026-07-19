<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller {
    public function index(Request $request) {
        $query = SchoolClass::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $perPage = $request->get('per_page', 15);
        if ($perPage === 'all' || $perPage === 'semua') {
            $paginateCount = 999999;
        } else {
            $paginateCount = in_array($perPage, [10, 20, 50, 100]) ? (int)$perPage : 15;
        }

        $items = $query->orderByRaw("FIELD(grade, 'X', 'XI', 'XII')")->orderBy('name')->paginate($paginateCount)->withQueryString();
        return view('admin.school-classes.index', compact('items'));
    }

    public function bulkDestroy(Request $request) {
        $ids = $request->input('ids', []);
        if (count($ids) > 0) {
            SchoolClass::whereIn('id', $ids)->delete();
            return redirect()->route('admin.school-classes.index')->with('success', count($ids) . ' data kelas berhasil dihapus secara massal.');
        }
        return redirect()->route('admin.school-classes.index')->with('error', 'Tidak ada data kelas yang dipilih.');
    }
    public function create() {
        return view('admin.school-classes.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'grade' => 'required|in:X,XI,XII',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:school_classes,slug',
        ]);
        SchoolClass::create($validated);
        return redirect()->route('admin.school-classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }
    public function edit(SchoolClass $schoolClass) {
        $item = $schoolClass;
        return view('admin.school-classes.form', compact('item'));
    }
    public function update(Request $request, SchoolClass $schoolClass) {
        $validated = $request->validate([
            'grade' => 'required|in:X,XI,XII',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:school_classes,slug,' . $schoolClass->id,
        ]);
        $schoolClass->update($validated);
        return redirect()->route('admin.school-classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }
    public function destroy(SchoolClass $schoolClass) {
        $schoolClass->delete();
        return redirect()->route('admin.school-classes.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function showImport() {
        return view('admin.school-classes.import');
    }

    public function importCsv(Request $request) {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn($h) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h))), $header);

        if (!in_array('nama_kelas', $header)) {
            fclose($handle);
            return back()->with('error', "Kolom 'nama_kelas' tidak ditemukan. Format: nama_kelas (opsional: tingkat)");
        }

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 1) continue;
            $data = array_combine($header, array_pad($row, count($header), ''));
            $name = trim($data['nama_kelas']);
            if (empty($name)) continue;

            $grade = isset($data['tingkat']) ? strtoupper(trim($data['tingkat'])) : $this->detectGrade($name);
            SchoolClass::firstOrCreate(
                ['name' => $name],
                ['grade' => $grade, 'slug' => \Illuminate\Support\Str::slug($name)]
            );
            $imported++;
        }
        fclose($handle);
        return redirect()->route('admin.school-classes.index')->with('success', "{$imported} data kelas berhasil diimport.");
    }

    private function detectGrade($className) {
        $className = strtoupper($className);
        if (str_starts_with($className, 'XII')) return 'XII';
        if (str_starts_with($className, 'XI')) return 'XI';
        if (str_starts_with($className, 'X')) return 'X';
        return 'X';
    }
}

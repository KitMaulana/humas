<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller {
    public function index(Request $request) {
        $query = Teacher::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.teachers.index', compact('items'));
    }
    public function create() {
        return view('admin.teachers.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'status' => 'required|in:PNS,Honorer,PPPK',
            'education' => 'nullable|string|max:255',
            'teaching_since' => 'nullable|integer|min:1900|max:' . date('Y'),
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('teachers', 'public');
        }
        Teacher::create($validated);
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }
    public function edit(Teacher $teacher) {
        $item = $teacher;
        return view('admin.teachers.form', compact('item'));
    }
    public function update(Request $request, Teacher $teacher) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'status' => 'required|in:PNS,Honorer,PPPK',
            'education' => 'nullable|string|max:255',
            'teaching_since' => 'nullable|integer|min:1900|max:' . date('Y'),
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            if ($teacher->photo_path) {
                Storage::disk('public')->delete($teacher->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_path')->store('teachers', 'public');
        }
        $teacher->update($validated);
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }
    public function destroy(Teacher $teacher) {
        if ($teacher->photo_path) {
            Storage::disk('public')->delete($teacher->photo_path);
        }
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }

    public function showImport() {
        return view('admin.teachers.import');
    }

    public function importCsv(Request $request) {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn($h) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h))), $header);

        $required = ['nama'];
        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom '{$col}' tidak ditemukan. Kolom wajib: nama. Opsional: nip, status, pendidikan, mengajar_sejak");
            }
        }

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 1) continue;
            $data = array_combine($header, array_pad($row, count($header), ''));
            Teacher::firstOrCreate(
                ['name' => trim($data['nama'])],
                [
                    'nip' => trim($data['nip'] ?? ''),
                    'status' => trim($data['status'] ?? 'Honorer'),
                    'education' => trim($data['pendidikan'] ?? ''),
                    'teaching_since' => !empty($data['mengajar_sejak']) ? (int)$data['mengajar_sejak'] : null,
                ]
            );
            $imported++;
        }
        fclose($handle);
        return redirect()->route('admin.teachers.index')->with('success', "{$imported} data guru berhasil diimport.");
    }
}

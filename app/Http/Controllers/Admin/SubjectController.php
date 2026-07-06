<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller {
    public function index(Request $request) {
        $query = Subject::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.subjects.index', compact('items'));
    }
    public function create() {
        return view('admin.subjects.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subjects,slug',
        ]);
        Subject::create($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }
    public function edit(Subject $subject) {
        $item = $subject;
        return view('admin.subjects.form', compact('item'));
    }
    public function update(Request $request, Subject $subject) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subjects,slug,' . $subject->id,
        ]);
        $subject->update($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }
    public function destroy(Subject $subject) {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    public function showImport() {
        return view('admin.subjects.import');
    }

    public function importCsv(Request $request) {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn($h) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h))), $header);

        if (!in_array('nama', $header)) {
            fclose($handle);
            return back()->with('error', "Kolom 'nama' tidak ditemukan. Format: nama");
        }

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 1) continue;
            $data = array_combine($header, array_pad($row, count($header), ''));
            $name = trim($data['nama']);
            if (empty($name)) continue;
            Subject::firstOrCreate(
                ['name' => $name],
                ['slug' => \Illuminate\Support\Str::slug($name)]
            );
            $imported++;
        }
        fclose($handle);
        return redirect()->route('admin.subjects.index')->with('success', "{$imported} mata pelajaran berhasil diimport.");
    }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AlumniStat;
use Illuminate\Http\Request;

class AlumniStatController extends Controller {
    public function index(Request $request) {
        $items = AlumniStat::orderBy('year', 'desc')->paginate(15);
        return view('admin.alumni-stats.index', compact('items'));
    }
    public function create() {
        return view('admin.alumni-stats.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y') . '|unique:alumni_stats,year',
            'college_count' => 'required|integer|min:0',
            'work_count' => 'required|integer|min:0',
            'entrepreneur_count' => 'required|integer|min:0',
        ]);
        AlumniStat::create($validated);
        return redirect()->route('admin.alumni-stats.index')->with('success', 'Statistik alumni berhasil ditambahkan.');
    }
    public function edit(AlumniStat $alumniStat) {
        $item = $alumniStat;
        return view('admin.alumni-stats.form', compact('item'));
    }
    public function update(Request $request, AlumniStat $alumniStat) {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y') . '|unique:alumni_stats,year,' . $alumniStat->id,
            'college_count' => 'required|integer|min:0',
            'work_count' => 'required|integer|min:0',
            'entrepreneur_count' => 'required|integer|min:0',
        ]);
        $alumniStat->update($validated);
        return redirect()->route('admin.alumni-stats.index')->with('success', 'Statistik alumni berhasil diperbarui.');
    }
    public function destroy(AlumniStat $alumniStat) {
        $alumniStat->delete();
        return redirect()->route('admin.alumni-stats.index')->with('success', 'Statistik alumni berhasil dihapus.');
    }
}

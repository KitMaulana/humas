<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller {
    public function index(Request $request) {
        $query = Achievement::query();
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $items = $query->orderBy('year', 'desc')->paginate(15)->withQueryString();
        return view('admin.achievements.index', compact('items'));
    }
    public function create() {
        return view('admin.achievements.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Siswa,Guru,Sekolah',
            'level' => 'nullable|string|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('achievements', 'public');
        }
        Achievement::create($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }
    public function edit(Achievement $achievement) {
        $item = $achievement;
        return view('admin.achievements.form', compact('item'));
    }
    public function update(Request $request, Achievement $achievement) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Siswa,Guru,Sekolah',
            'level' => 'nullable|string|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            if ($achievement->photo_path) {
                Storage::disk('public')->delete($achievement->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_path')->store('achievements', 'public');
        }
        $achievement->update($validated);
        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil diperbarui.');
    }
    public function destroy(Achievement $achievement) {
        if ($achievement->photo_path) {
            Storage::disk('public')->delete($achievement->photo_path);
        }
        $achievement->delete();
        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}

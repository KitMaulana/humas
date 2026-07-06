<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller {
    public function index(Request $request) {
        $query = Facility::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }
        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.facilities.index', compact('items'));
    }
    public function create() {
        return view('admin.facilities.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'count' => 'required|integer|min:1',
            'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('facilities', 'public');
        }
        Facility::create($validated);
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }
    public function edit(Facility $facility) {
        $item = $facility;
        return view('admin.facilities.form', compact('item'));
    }
    public function update(Request $request, Facility $facility) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'count' => 'required|integer|min:1',
            'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            if ($facility->photo_path) {
                Storage::disk('public')->delete($facility->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_path')->store('facilities', 'public');
        }
        $facility->update($validated);
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }
    public function destroy(Facility $facility) {
        if ($facility->photo_path) {
            Storage::disk('public')->delete($facility->photo_path);
        }
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}

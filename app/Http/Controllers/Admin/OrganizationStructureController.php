<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\OrganizationStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationStructureController extends Controller {
    public function index(Request $request) {
        $items = OrganizationStructure::orderBy('sort_order')->paginate(25);
        return view('admin.organization-structures.index', compact('items'));
    }
    public function create() {
        return view('admin.organization-structures.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('organization', 'public');
        }
        OrganizationStructure::create($validated);
        return redirect()->route('admin.organization-structures.index')->with('success', 'Struktur organisasi berhasil ditambahkan.');
    }
    public function edit(OrganizationStructure $organizationStructure) {
        $item = $organizationStructure;
        return view('admin.organization-structures.form', compact('item'));
    }
    public function update(Request $request, OrganizationStructure $organizationStructure) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'photo_path' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo_path')) {
            if ($organizationStructure->photo_path) {
                Storage::disk('public')->delete($organizationStructure->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_path')->store('organization', 'public');
        }
        $organizationStructure->update($validated);
        return redirect()->route('admin.organization-structures.index')->with('success', 'Struktur organisasi berhasil diperbarui.');
    }
    public function destroy(OrganizationStructure $organizationStructure) {
        if ($organizationStructure->photo_path) {
            Storage::disk('public')->delete($organizationStructure->photo_path);
        }
        $organizationStructure->delete();
        return redirect()->route('admin.organization-structures.index')->with('success', 'Struktur organisasi berhasil dihapus.');
    }
}

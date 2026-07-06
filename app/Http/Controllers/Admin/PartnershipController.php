<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Partnership;
use Illuminate\Http\Request;

class PartnershipController extends Controller {
    public function index(Request $request) {
        $query = Partnership::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.partnerships.index', compact('items'));
    }
    public function create() {
        return view('admin.partnerships.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'partner_type' => 'nullable|string|max:255',
        ]);
        Partnership::create($validated);
        return redirect()->route('admin.partnerships.index')->with('success', 'Kemitraan berhasil ditambahkan.');
    }
    public function edit(Partnership $partnership) {
        $item = $partnership;
        return view('admin.partnerships.form', compact('item'));
    }
    public function update(Request $request, Partnership $partnership) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'partner_type' => 'nullable|string|max:255',
        ]);
        $partnership->update($validated);
        return redirect()->route('admin.partnerships.index')->with('success', 'Kemitraan berhasil diperbarui.');
    }
    public function destroy(Partnership $partnership) {
        $partnership->delete();
        return redirect()->route('admin.partnerships.index')->with('success', 'Kemitraan berhasil dihapus.');
    }
}

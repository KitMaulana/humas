<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller {
    public function index(Request $request) {
        $query = Staff::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('position', 'like', '%' . $request->search . '%');
        }
        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.staff.index', compact('items'));
    }
    public function create() {
        return view('admin.staff.form');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);
        Staff::create($validated);
        return redirect()->route('admin.staff.index')->with('success', 'Data staf berhasil ditambahkan.');
    }
    public function edit(Staff $staff) {
        $item = $staff;
        return view('admin.staff.form', compact('item'));
    }
    public function update(Request $request, Staff $staff) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);
        $staff->update($validated);
        return redirect()->route('admin.staff.index')->with('success', 'Data staf berhasil diperbarui.');
    }
    public function destroy(Staff $staff) {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Data staf berhasil dihapus.');
    }
}

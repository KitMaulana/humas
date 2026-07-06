<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller {
    public function edit() {
        $item = SchoolProfile::firstOrCreate([], [
            'name' => 'SMAN 1 Ciruas',
        ]);
        return view('admin.school-profile.form', compact('item'));
    }
    public function update(Request $request) {
        $item = SchoolProfile::first();
        if (!$item) {
            $item = SchoolProfile::create(['name' => 'SMAN 1 Ciruas']);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'google_maps_iframe' => 'nullable|string',
            'hero_image_1' => 'nullable|image|max:2048',
            'hero_image_2' => 'nullable|image|max:2048',
            'hero_image_3' => 'nullable|image|max:2048',
            'hero_image_4' => 'nullable|image|max:2048',
            'hero_image_5' => 'nullable|image|max:2048',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "hero_image_$i";
            if ($request->hasFile($fieldName)) {
                if ($item->$fieldName) {
                    Storage::disk('public')->delete($item->$fieldName);
                }
                $validated[$fieldName] = $request->file($fieldName)->store('hero', 'public');
            } elseif ($request->boolean("delete_$fieldName")) {
                if ($item->$fieldName) {
                    Storage::disk('public')->delete($item->$fieldName);
                }
                $validated[$fieldName] = null;
            }
        }

        $item->update($validated);
        return redirect()->route('admin.school-profile.edit')->with('success', 'Profil sekolah berhasil diperbarui.');
    }
}

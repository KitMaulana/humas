<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniUniversity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AlumniUniversityController extends Controller
{
    public function index(Request $request)
    {
        $query = AlumniUniversity::query();
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        $items = $query->orderBy('year', 'desc')->orderBy('category')->orderBy('count', 'desc')->paginate(15);
        $years = AlumniUniversity::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        return view('admin.alumni-universities.index', compact('items', 'years'));
    }

    public function create()
    {
        return view('admin.alumni-universities.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:ptn,ptn-lokal,pts',
            'count' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:50',
        ]);
        if (empty($validated['icon'])) {
            $validated['icon'] = '🏫';
        }
        AlumniUniversity::create($validated);
        return redirect()->route('admin.alumni-universities.index')->with('success', 'Rincian kampus alumni berhasil ditambahkan.');
    }

    public function edit(AlumniUniversity $alumniUniversity)
    {
        $item = $alumniUniversity;
        return view('admin.alumni-universities.form', compact('item'));
    }

    public function update(Request $request, AlumniUniversity $alumniUniversity)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:ptn,ptn-lokal,pts',
            'count' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:50',
        ]);
        if (empty($validated['icon'])) {
            $validated['icon'] = '🏫';
        }
        $alumniUniversity->update($validated);
        return redirect()->route('admin.alumni-universities.index')->with('success', 'Rincian kampus alumni berhasil diperbarui.');
    }

    public function destroy(AlumniUniversity $alumniUniversity)
    {
        $alumniUniversity->delete();
        return redirect()->route('admin.alumni-universities.index')->with('success', 'Rincian kampus alumni berhasil dihapus.');
    }

    public function showImport()
    {
        return view('admin.alumni-universities.import');
    }

    public function importCsv(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        
        // Clear UTF-8 BOM if present
        $header = array_map(fn($h) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h))), $header);

        $required = ['tahun', 'nama_kampus', 'kategori', 'jumlah'];
        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom '{$col}' tidak ditemukan. Kolom wajib: tahun, nama_kampus, kategori, jumlah. Opsional: icon.");
            }
        }

        $imported = 0;
        $updated = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 1 || empty($row[0])) continue;
            
            $data = array_combine($header, array_pad($row, count($header), ''));
            
            $year = (int)trim($data['tahun']);
            $name = trim($data['nama_kampus']);
            $category = strtolower(trim($data['kategori']));
            $count = (int)trim($data['jumlah']);
            $icon = trim($data['icon'] ?? '🏫');

            if (empty($icon)) {
                $icon = '🏫';
            }

            // Map aliases for category
            if ($category === 'ptn favorit') $category = 'ptn';
            if ($category === 'ptn banten') $category = 'ptn-lokal';

            // Validate category enum
            if (!in_array($category, ['ptn', 'ptn-lokal', 'pts'])) {
                continue;
            }

            // Search for existing entry for this year and university name
            $uni = AlumniUniversity::where('year', $year)->where('name', $name)->first();
            if ($uni) {
                $uni->update([
                    'category' => $category,
                    'count' => $count,
                    'icon' => $icon,
                ]);
                $updated++;
            } else {
                AlumniUniversity::create([
                    'year' => $year,
                    'name' => $name,
                    'category' => $category,
                    'count' => $count,
                    'icon' => $icon,
                ]);
                $imported++;
            }
        }

        fclose($handle);
        return redirect()->route('admin.alumni-universities.index')->with('success', "Import selesai. {$imported} data ditambahkan, {$updated} data diperbarui.");
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_alumni_universitas.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Header CSV
            fputcsv($file, ['tahun', 'nama_kampus', 'kategori', 'jumlah', 'icon']);
            // Contoh baris
            fputcsv($file, ['2024', 'Universitas Indonesia', 'ptn', '28', '🏛️']);
            fputcsv($file, ['2024', 'Universitas Sultan Ageng Tirtayasa', 'ptn-lokal', '64', '🏫']);
            fputcsv($file, ['2024', 'Universitas Bina Nusantara', 'pts', '14', '💻']);
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}

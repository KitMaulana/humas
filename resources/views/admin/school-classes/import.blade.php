@extends('layouts.admin')
@section('page-title', 'Import Data Kelas')
@section('content')
<div class="page-header">
    <div>
        <h1>Import Data Kelas</h1>
        <p>Upload file CSV untuk menambahkan data kelas secara bulk</p>
    </div>
    <a href="{{ route('admin.school-classes.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-file-upload"></i> Upload CSV</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.school-classes.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">File CSV <span class="required">*</span></label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <small style="color:#64748b;">Maksimum 5MB. Format: CSV (comma-separated)</small>
                </div>
                <div class="form-actions" style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import Sekarang</button>
                    <a href="{{ route('admin.school-classes.template') }}" class="btn btn-outline"><i class="fas fa-download"></i> Download Template</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class="fas fa-info-circle"></i> Panduan Format CSV</h3></div>
        <div class="card-body">
            <p style="margin-bottom:12px;color:#475569;font-size:13px;">File CSV harus memiliki header berikut:</p>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Kolom</th><th>Contoh</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td><code>nama_kelas</code></td><td>X MIPA 1</td><td>Nama kelas / rombel (wajib)</td></tr>
                        <tr><td><code>tingkat</code></td><td>X</td><td>X, XI, atau XII (opsional, auto-detect dari nama)</td></tr>
                    </tbody>
                </table>
            </div>
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 16px;margin-top:16px;">
                <strong style="color:#0369a1;font-size:12px;"><i class="fas fa-lightbulb"></i> TIP</strong>
                <p style="color:#0c4a6e;font-size:12px;margin-top:4px;">Kolom <code>tingkat</code> opsional — jika dikosongkan, tingkat akan otomatis dideteksi dari nama kelas (misal "X MIPA 1" → tingkat X). Kelas yang sudah ada tidak akan di-duplikasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection

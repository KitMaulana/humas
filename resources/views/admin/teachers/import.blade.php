@extends('layouts.admin')
@section('page-title', 'Import Data Guru')
@section('content')
<div class="page-header">
    <div>
        <h1>Import Data Guru</h1>
        <p>Upload file CSV untuk menambahkan data guru secara bulk</p>
    </div>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-file-upload"></i> Upload CSV</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.teachers.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">File CSV <span class="required">*</span></label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <small style="color:#64748b;">Maksimum 5MB. Format: CSV (comma-separated)</small>
                </div>
                <div class="form-actions" style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import Sekarang</button>
                    <a href="{{ route('admin.teachers.template') }}" class="btn btn-outline"><i class="fas fa-download"></i> Download Template</a>
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
                        <tr><td><code>nama</code></td><td>Budi Santoso, S.Pd.</td><td>Nama lengkap guru (wajib)</td></tr>
                        <tr><td><code>nip</code></td><td>198501012010011001</td><td>NIP, kosongkan jika tidak ada</td></tr>
                        <tr><td><code>status</code></td><td>PNS</td><td>PNS, Honorer, atau PPPK</td></tr>
                        <tr><td><code>pendidikan</code></td><td>S1 Matematika</td><td>Pendidikan terakhir</td></tr>
                        <tr><td><code>mengajar_sejak</code></td><td>2010</td><td>Tahun mulai mengajar</td></tr>
                    </tbody>
                </table>
            </div>
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 16px;margin-top:16px;">
                <strong style="color:#0369a1;font-size:12px;"><i class="fas fa-lightbulb"></i> TIP</strong>
                <p style="color:#0c4a6e;font-size:12px;margin-top:4px;">Guru dengan nama yang sama tidak akan di-duplikasi. Kolom <code>nama</code> wajib diisi, kolom lainnya opsional.</p>
            </div>
        </div>
    </div>
</div>
@endsection

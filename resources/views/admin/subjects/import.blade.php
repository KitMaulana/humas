@extends('layouts.admin')
@section('page-title', 'Import Mata Pelajaran')
@section('content')
<div class="page-header">
    <div>
        <h1>Import Mata Pelajaran</h1>
        <p>Upload file CSV untuk menambahkan mata pelajaran secara bulk</p>
    </div>
    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-file-upload"></i> Upload CSV</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.subjects.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">File CSV <span class="required">*</span></label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <small style="color:#64748b;">Maksimum 5MB. Format: CSV (comma-separated)</small>
                </div>
                <div class="form-actions" style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import Sekarang</button>
                    <a href="{{ route('admin.subjects.template') }}" class="btn btn-outline"><i class="fas fa-download"></i> Download Template</a>
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
                        <tr><td><code>nama</code></td><td>Matematika</td><td>Nama mata pelajaran (wajib)</td></tr>
                    </tbody>
                </table>
            </div>
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 16px;margin-top:16px;">
                <strong style="color:#0369a1;font-size:12px;"><i class="fas fa-lightbulb"></i> TIP</strong>
                <p style="color:#0c4a6e;font-size:12px;margin-top:4px;">Mata pelajaran dengan nama yang sama tidak akan di-duplikasi. Slug akan di-generate otomatis dari nama.</p>
            </div>
        </div>
    </div>
</div>
@endsection

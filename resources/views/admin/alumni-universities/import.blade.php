@extends('layouts.admin')
@section('page-title', 'Import Detail Perguruan Tinggi Alumni')
@section('content')
<div class="page-header">
    <div>
        <h1>Import Detail PT Alumni</h1>
        <p>Upload berkas CSV untuk menambahkan rincian penerimaan kampus secara massal</p>
    </div>
    <a href="{{ route('admin.alumni-universities.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-file-upload"></i> Unggah File CSV</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.alumni-universities.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Berkas CSV <span class="required">*</span></label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <small style="color:#64748b; margin-top: 4px; display: block;">Maksimum 5MB. Format: CSV (koma sebagai pemisah)</small>
                </div>
                <div class="form-actions" style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import Sekarang</button>
                    <a href="{{ route('admin.alumni-universities.template') }}" class="btn btn-outline"><i class="fas fa-download"></i> Unduh Template CSV</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class="fas fa-info-circle"></i> Panduan Format CSV</h3></div>
        <div class="card-body">
            <p style="margin-bottom:12px;color:#475569;font-size:13px;">Berkas CSV Anda harus memiliki tajuk kolom (header) berikut:</p>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Kolom</th><th>Contoh</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td><code>tahun</code></td><td>2024</td><td>Tahun angkatan kelulusan (wajib)</td></tr>
                        <tr><td><code>nama_kampus</code></td><td>Universitas Indonesia</td><td>Nama perguruan tinggi (wajib)</td></tr>
                        <tr><td><code>kategori</code></td><td>ptn</td><td>Kategori kampus: <code>ptn</code>, <code>ptn-lokal</code> (Wilayah Banten), atau <code>pts</code> (wajib)</td></tr>
                        <tr><td><code>jumlah</code></td><td>28</td><td>Jumlah siswa yang diterima (wajib)</td></tr>
                        <tr><td><code>icon</code></td><td>🏛️</td><td>Emoji penanda ikon kampus (opsional, default: 🏫)</td></tr>
                    </tbody>
                </table>
            </div>
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 16px;margin-top:16px;">
                <strong style="color:#0369a1;font-size:12px;"><i class="fas fa-lightbulb"></i> CATATAN</strong>
                <p style="color:#0c4a6e;font-size:12px;margin-top:4px;">Sistem akan melakukan pembaruan jumlah (*update*) otomatis jika kombinasi <code>tahun</code> dan <code>nama_kampus</code> sudah terdaftar sebelumnya di database untuk mencegah data ganda.</p>
            </div>
        </div>
    </div>
</div>
@endsection

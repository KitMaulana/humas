@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Rincian Kampus Alumni' : 'Tambah Rincian Kampus Alumni')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Rincian Kampus Alumni' : 'Tambah Rincian Kampus Alumni' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail rincian kampus penyerapan alumni' : 'Tambahkan detail rincian kampus baru' }}</p>
    </div>
    <a href="{{ route('admin.alumni-universities.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.alumni-universities.update', $item) : route('admin.alumni-universities.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tahun Lulus / Angkatan <span class="required">*</span></label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $item->year ?? date('Y')) }}" min="1900" max="{{ date('Y') }}" placeholder="Contoh: 2024" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Perguruan Tinggi <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: Universitas Indonesia" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori Kampus <span class="required">*</span></label>
                    <select name="category" class="form-control" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="ptn" {{ old('category', $item->category ?? '') == 'ptn' ? 'selected' : '' }}>PTN Favorit</option>
                        <option value="ptn-lokal" {{ old('category', $item->category ?? '') == 'ptn-lokal' ? 'selected' : '' }}>PTN Banten</option>
                        <option value="pts" {{ old('category', $item->category ?? '') == 'pts' ? 'selected' : '' }}>Perguruan Tinggi Swasta (PTS)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Diterima <span class="required">*</span></label>
                    <input type="number" name="count" class="form-control" value="{{ old('count', $item->count ?? 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Emoji Ikon (Opsional)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $item->icon ?? '🏫') }}" placeholder="Contoh: 🏛️, 🏫, 🌾, 💻">
                    <small style="color:#64748b; margin-top: 4px; display: block;">Emoji visual pendukung logo universitas. Contoh: 🏛️ (UI), 🌾 (IPB).</small>
                </div>
            </div>
            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                <a href="{{ route('admin.alumni-universities.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

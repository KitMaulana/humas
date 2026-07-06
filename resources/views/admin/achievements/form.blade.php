@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Prestasi' : 'Tambah Prestasi')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Prestasi' : 'Tambah Prestasi' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail informasi prestasi' : 'Tambahkan prestasi baru' }}</p>
    </div>
    <a href="{{ route('admin.achievements.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.achievements.update', $item) : route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Judul Prestasi <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" placeholder="Contoh: Juara 1 Olimpiade Fisika Nasional" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select name="category" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Siswa" {{ old('category', $item->category ?? '') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="Guru" {{ old('category', $item->category ?? '') == 'Guru' ? 'selected' : '' }}>Guru</option>
                        <option value="Sekolah" {{ old('category', $item->category ?? '') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat Prestasi</label>
                    <select name="level" class="form-control">
                        <option value="">Pilih Tingkat</option>
                        <option value="Internasional" {{ old('level', $item->level ?? '') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                        <option value="Nasional" {{ old('level', $item->level ?? '') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="Provinsi" {{ old('level', $item->level ?? '') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                        <option value="Kabupaten" {{ old('level', $item->level ?? '') == 'Kabupaten' ? 'selected' : '' }}>Kabupaten/Kota</option>
                        <option value="Kecamatan" {{ old('level', $item->level ?? '') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Perolehan <span class="required">*</span></label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $item->year ?? '') }}" min="1900" max="{{ date('Y') }}" placeholder="Tahun perolehan" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto / Dokumentasi</label>
                    <div class="file-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau drag file gambar untuk upload</p>
                        <input type="file" name="photo_path" accept="image/*">
                        <div class="file-preview">
                            @if(isset($item) && $item->photo_path)
                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="Foto">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Deskripsikan prestasi secara singkat...">{{ old('description', $item->description ?? '') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.achievements.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

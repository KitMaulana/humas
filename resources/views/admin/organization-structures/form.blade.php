@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Pengurus' : 'Tambah Pengurus')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Pengurus' : 'Tambah Pengurus' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail informasi pengurus' : 'Tambahkan pengurus baru ke bagan organisasi' }}</p>
    </div>
    <a href="{{ route('admin.organization-structures.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.organization-structures.update', $item) : route('admin.organization-structures.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Pengurus <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Nama beserta gelar" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jabatan / Posisi <span class="required">*</span></label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $item->position ?? '') }}" placeholder="Contoh: Kepala Sekolah, Waka Kurikulum" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Urutan Tampilan <span class="required">*</span></label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" required>
                    <small class="form-hint">Digunakan untuk urutan posisi dari atas ke bawah</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
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
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.organization-structures.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Fasilitas' : 'Tambah Fasilitas')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail sarana prasarana' : 'Tambahkan sarana prasarana baru' }}</p>
    </div>
    <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.facilities.update', $item) : route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Fasilitas <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: Laboratorium Kimia" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $item->category ?? '') }}" placeholder="Contoh: Ruang Belajar, Lab, Lapangan" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Unit <span class="required">*</span></label>
                    <input type="number" name="count" class="form-control" value="{{ old('count', $item->count ?? 1) }}" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi <span class="required">*</span></label>
                    <select name="condition" class="form-control" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik" {{ old('condition', $item->condition ?? '') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('condition', $item->condition ?? '') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('condition', $item->condition ?? '') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Fasilitas</label>
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
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

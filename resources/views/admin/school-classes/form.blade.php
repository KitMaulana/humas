@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Kelas' : 'Tambah Kelas')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Kelas' : 'Tambah Kelas' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail kelas' : 'Tambahkan kelas baru ke sistem' }}</p>
    </div>
    <a href="{{ route('admin.school-classes.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.school-classes.update', $item) : route('admin.school-classes.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tingkat Kelas <span class="required">*</span></label>
                    <select name="grade" class="form-control" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="X" {{ old('grade', $item->grade ?? '') == 'X' ? 'selected' : '' }}>Kelas X</option>
                        <option value="XI" {{ old('grade', $item->grade ?? '') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                        <option value="XII" {{ old('grade', $item->grade ?? '') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Rombel <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: X-1 atau XI IPA-1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Slug <span class="required">*</span></label>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}" placeholder="Contoh: x-1 atau xi-ipa-1" required>
                    <small class="form-hint">Digunakan untuk URL halaman kelas</small>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.school-classes.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

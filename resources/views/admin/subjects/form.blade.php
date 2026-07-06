@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Mapel' : 'Tambah Mapel')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Mapel' : 'Tambah Mapel' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail mata pelajaran' : 'Tambahkan mata pelajaran baru' }}</p>
    </div>
    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.subjects.update', $item) : route('admin.subjects.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Mata Pelajaran <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: Matematika Wajib" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Slug <span class="required">*</span></label>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}" placeholder="Contoh: matematika-wajib" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Staf' : 'Tambah Staf')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Staf' : 'Tambah Staf' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail informasi staf' : 'Tambahkan staf baru ke sistem' }}</p>
    </div>
    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.staff.update', $item) : route('admin.staff.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Nama staf" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jabatan <span class="required">*</span></label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $item->position ?? '') }}" placeholder="Contoh: Kepala Tata Usaha, Pustakawan" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Kepegawaian</label>
                    <input type="text" name="status" class="form-control" value="{{ old('status', $item->status ?? '') }}" placeholder="Contoh: PNS, Honorer">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

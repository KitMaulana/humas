@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Guru' : 'Tambah Guru')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Guru' : 'Tambah Guru' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail informasi guru' : 'Tambahkan guru baru ke sistem' }}</p>
    </div>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.teachers.update', $item) : route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Nama beserta gelar" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $item->nip ?? '') }}" placeholder="Nomor Induk Pegawai">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Kepegawaian <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="PNS" {{ old('status', $item->status ?? '') == 'PNS' ? 'selected' : '' }}>PNS</option>
                        <option value="Honorer" {{ old('status', $item->status ?? '') == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                        <option value="PPPK" {{ old('status', $item->status ?? '') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <input type="text" name="education" class="form-control" value="{{ old('education', $item->education ?? '') }}" placeholder="Contoh: S1 Pendidikan Matematika">
                </div>
                <div class="form-group">
                    <label class="form-label">Mengajar Sejak (Tahun)</label>
                    <input type="number" name="teaching_since" class="form-control" value="{{ old('teaching_since', $item->teaching_since ?? '') }}" min="1900" max="{{ date('Y') }}" placeholder="Contoh: 2015">
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
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

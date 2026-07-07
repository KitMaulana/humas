@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Admin' : 'Tambah Admin')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Admin' : 'Tambah Admin' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail pengguna administrator' : 'Tambahkan administrator baru' }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.users.update', $item) : route('admin.users.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: Admin Humas" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $item->email ?? '') }}" placeholder="Contoh: admin@gmail.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi {!! !isset($item) ? '<span class="required">*</span>' : '<span style="font-size:11px;color:#999;font-weight:normal;">(Kosongkan jika tidak ingin diubah)</span>' !!}</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter" {{ !isset($item) ? 'required' : '' }}>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Kata Sandi {!! !isset($item) ? '<span class="required">*</span>' : '' !!}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" {{ !isset($item) ? 'required' : '' }}>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

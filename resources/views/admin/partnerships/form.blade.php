@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Mitra' : 'Tambah Mitra')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Mitra' : 'Tambah Mitra' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail informasi mitra' : 'Tambahkan mitra kerja sama baru' }}</p>
    </div>
    <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.partnerships.update', $item) : route('admin.partnerships.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Mitra / Perusahaan <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: PT Indah Kiat, Universitas Banten" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis / Tipe Kemitraan</label>
                    <input type="text" name="partner_type" class="form-control" value="{{ old('partner_type', $item->partner_type ?? '') }}" placeholder="Contoh: Industri, Perguruan Tinggi, Instansi Pemerintah">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Deskripsi / Keterangan Kerjasama</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Tuliskan keterangan detail mengenai bentuk kerjasama...">{{ old('description', $item->description ?? '') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

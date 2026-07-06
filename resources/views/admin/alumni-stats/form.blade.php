@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Statistik Lulusan' : 'Tambah Statistik Lulusan')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Statistik Alumni' : 'Tambah Statistik Alumni' }}</h1>
        <p>{{ isset($item) ? 'Ubah data penyerapan lulusan' : 'Tambahkan data penyerapan lulusan baru' }}</p>
    </div>
    <a href="{{ route('admin.alumni-stats.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.alumni-stats.update', $item) : route('admin.alumni-stats.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tahun Lulus / Angkatan <span class="required">*</span></label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $item->year ?? '') }}" min="1900" max="{{ date('Y') }}" placeholder="Contoh: 2024" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Kuliah (Pendidikan Tinggi) <span class="required">*</span></label>
                    <input type="number" name="college_count" class="form-control" value="{{ old('college_count', $item->college_count ?? 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Kerja <span class="required">*</span></label>
                    <input type="number" name="work_count" class="form-control" value="{{ old('work_count', $item->work_count ?? 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Wirausaha <span class="required">*</span></label>
                    <input type="number" name="entrepreneur_count" class="form-control" value="{{ old('entrepreneur_count', $item->entrepreneur_count ?? 0) }}" min="0" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.alumni-stats.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

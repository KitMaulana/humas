@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Statistik Siswa' : 'Tambah Statistik Siswa')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Statistik Siswa' : 'Tambah Statistik Siswa' }}</h1>
        <p>{{ isset($item) ? 'Ubah data jumlah siswa kelas' : 'Tambahkan data jumlah siswa kelas baru' }}</p>
    </div>
    <a href="{{ route('admin.student-stats.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.student-stats.update', $item) : route('admin.student-stats.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                    <input type="text" name="academic_year" class="form-control" value="{{ old('academic_year', $item->academic_year ?? '2025/2026') }}" placeholder="Contoh: 2025/2026" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas <span class="required">*</span></label>
                    <select name="school_class_id" class="form-control" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('school_class_id', $item->school_class_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Siswa Laki-laki (L) <span class="required">*</span></label>
                    <input type="number" name="male_count" class="form-control" value="{{ old('male_count', $item->male_count ?? 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Siswa Perempuan (P) <span class="required">*</span></label>
                    <input type="number" name="female_count" class="form-control" value="{{ old('female_count', $item->female_count ?? 0) }}" min="0" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.student-stats.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

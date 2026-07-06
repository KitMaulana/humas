@extends('layouts.admin')
@section('page-title', isset($item) ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($item) ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h1>
        <p>{{ isset($item) ? 'Ubah detail jadwal pelajaran' : 'Tambahkan jadwal pelajaran baru' }}</p>
    </div>
    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ isset($item) ? route('admin.schedules.update', $item) : route('admin.schedules.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Hari <span class="required">*</span></label>
                    <select name="day" class="form-control" required>
                        <option value="">Pilih Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            <option value="{{ $day }}" {{ old('day', $item->day ?? '') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Pelajaran Ke- <span class="required">*</span></label>
                    <select name="lesson_number" class="form-control" required>
                        <option value="">Pilih Jam Pelajaran</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot['number'] }}" {{ old('lesson_number', $item->lesson_number ?? '') == $slot['number'] ? 'selected' : '' }}>
                                JP {{ $slot['number'] }} — {{ $slot['start'] }} s.d {{ $slot['end'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Tipe Jadwal <span class="required">*</span></label>
                    <select id="schedule-type" class="form-control" required>
                        <option value="subject" {{ !isset($item) || $item->title === null ? 'selected' : '' }}>Mata Pelajaran (Kelas Tertentu)</option>
                        <option value="global" {{ isset($item) && $item->title !== null ? 'selected' : '' }}>Agenda Bersama (Semua Kelas — misal: Upacara, Istirahat, Sholat)</option>
                    </select>
                </div>

                <div id="global-fields-wrap" style="display: none; grid-column: span 2;">
                    <div class="form-group full-width">
                        <label class="form-label">Nama Agenda / Kegiatan <span class="required">*</span></label>
                        <input type="text" name="title" id="title-input" class="form-control" value="{{ old('title', $item->title ?? '') }}" placeholder="Contoh: Upacara Bendera, Istirahat Pertama, Sholat Dzuhur Berjamaah">
                    </div>
                </div>

                <div id="subject-fields-wrap" style="display: contents;">
                    <div class="form-group">
                        <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                        <select name="subject_id" class="form-control" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id', $item->subject_id ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guru Pengajar <span class="required">*</span></label>
                        <select name="teacher_id" class="form-control" required>
                            <option value="">Pilih Guru</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('teacher_id', $item->teacher_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
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
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('schedule-type');
    const subjectFields = document.getElementById('subject-fields-wrap');
    const globalFields = document.getElementById('global-fields-wrap');

    const subjectInputs = subjectFields.querySelectorAll('select');
    const globalInput = document.getElementById('title-input');

    function toggleFields() {
        if (typeSelect.value === 'global') {
            subjectFields.style.display = 'none';
            globalFields.style.display = 'grid'; // display as grid under form-grid
            
            subjectInputs.forEach(input => input.required = false);
            globalInput.required = true;
        } else {
            subjectFields.style.display = 'contents';
            globalFields.style.display = 'none';
            
            subjectInputs.forEach(input => input.required = true);
            globalInput.required = false;
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields(); // run once on load
});
</script>
@endsection

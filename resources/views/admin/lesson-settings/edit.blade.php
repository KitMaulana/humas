@extends('layouts.admin')
@section('page-title', 'Pengaturan')
@section('content')
<div class="page-header">
    <div>
        <h1>Pengaturan</h1>
        <p>Konfigurasi jam pelajaran (JP), profil sekolah, dan slide banner utama</p>
    </div>
</div>

<div class="settings-tabs" style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 12px;">
    <a href="{{ route('admin.lesson-settings.edit') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-clock"></i> Jam Pelajaran (JP)
    </a>
    <a href="{{ route('admin.school-profile.edit') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-school"></i> Profil & Slide Banner
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    {{-- FORM --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-cog"></i> Konfigurasi</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.lesson-settings.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Durasi Per Jam Pelajaran <span class="required">*</span></label>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="number" name="lesson_duration" class="form-control" value="{{ old('lesson_duration', $setting->lesson_duration) }}" min="20" max="120" required>
                            <span style="color:#64748b;font-size:13px;white-space:nowrap;">menit</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Mulai Pelajaran Pertama <span class="required">*</span></label>
                        <input type="time" name="first_lesson_start" class="form-control" value="{{ old('first_lesson_start', substr($setting->first_lesson_start, 0, 5)) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Jam Pelajaran Per Hari <span class="required">*</span></label>
                        <input type="number" name="total_lessons" class="form-control" value="{{ old('total_lessons', $setting->total_lessons) }}" min="1" max="15" required>
                    </div>
                </div>

                <h4 style="margin: 20px 0 12px; color: #1e293b; font-size: 14px;"><i class="fas fa-coffee" style="color:#f59e0b;"></i> Istirahat Pertama</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Setelah Jam Pelajaran Ke-</label>
                        <input type="number" name="break_after_lesson" class="form-control" value="{{ old('break_after_lesson', $setting->break_after_lesson) }}" min="1" max="10" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi Istirahat</label>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="number" name="break_duration" class="form-control" value="{{ old('break_duration', $setting->break_duration) }}" min="5" max="60" required>
                            <span style="color:#64748b;font-size:13px;white-space:nowrap;">menit</span>
                        </div>
                    </div>
                </div>

                <h4 style="margin: 20px 0 12px; color: #1e293b; font-size: 14px;"><i class="fas fa-coffee" style="color:#f59e0b;"></i> Istirahat Kedua <span style="color:#94a3b8;font-weight:400;">(opsional)</span></h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Setelah Jam Pelajaran Ke-</label>
                        <input type="number" name="break2_after_lesson" class="form-control" value="{{ old('break2_after_lesson', $setting->break2_after_lesson) }}" min="1" max="10" placeholder="Kosongkan jika tidak ada">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi Istirahat</label>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="number" name="break2_duration" class="form-control" value="{{ old('break2_duration', $setting->break2_duration) }}" min="5" max="60" placeholder="-">
                            <span style="color:#64748b;font-size:13px;white-space:nowrap;">menit</span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- PREVIEW --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-clock"></i> Preview Jam Pelajaran</h3></div>
        <div class="card-body">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>JP Ke-</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $slot)
                            <tr>
                                <td><strong>{{ $slot['number'] }}</strong></td>
                                <td>{{ $slot['start'] }}</td>
                                <td>{{ $slot['end'] }}</td>
                                <td>
                                    @if($setting->break_after_lesson == $slot['number'])
                                        <span class="badge badge-warning"><i class="fas fa-coffee"></i> Istirahat setelah ini ({{ $setting->break_duration }} menit)</span>
                                    @elseif($setting->break2_after_lesson && $setting->break2_after_lesson == $slot['number'])
                                        <span class="badge badge-warning"><i class="fas fa-coffee"></i> Istirahat ke-2 ({{ $setting->break2_duration }} menit)</span>
                                    @else
                                        <span style="color:#94a3b8;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

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
            <div style="background: rgba(59,130,246,0.1); border-left: 4px solid #3b82f6; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                <h4 style="color:#1e3a8a; margin: 0 0 6px 0; font-size: 13px;"><i class="fas fa-info-circle"></i> Info Jadwal Fleksibel Harian</h4>
                <p style="color:#1e40af; margin: 0; font-size: 12px; line-height: 1.5;">
                    Sistem saat ini menggunakan konfigurasi jam pelajaran khusus per hari (Senin, Selasa-Kamis, Jumat) secara otomatis guna mengakomodasi perbedaan durasi KBM dan kegiatan seperti Upacara & Kajian Islami secara presisi sesuai jadwal resmi terbaru.
                </p>
            </div>
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
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <h3><i class="fas fa-clock"></i> Preview Jam Pelajaran</h3>
            <div style="display: flex; gap: 4px;">
                <button type="button" class="btn btn-sm btn-primary day-tab-btn" data-day="senin" onclick="switchDayTab('senin')">Senin</button>
                <button type="button" class="btn btn-sm btn-outline day-tab-btn" data-day="selasa" onclick="switchDayTab('selasa')">Selasa-Kamis</button>
                <button type="button" class="btn btn-sm btn-outline day-tab-btn" data-day="jumat" onclick="switchDayTab('jumat')">Jumat</button>
            </div>
        </div>
        <div class="card-body">
            <!-- TAB SENIN -->
            <div id="tab-senin" class="day-tab-content">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>JP / Kegiatan</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: rgba(59,130,246,0.05);">
                                <td><span class="badge badge-info">Kegiatan</span></td>
                                <td><strong>07:00</strong></td>
                                <td><strong>08:10</strong></td>
                                <td style="color:#1e3a8a; font-weight: 500;">UPACARA BENDERA / PEMBIASAAN</td>
                            </tr>
                            @foreach($setting->getTimeSlotsForDay('Senin') as $slot)
                                <tr>
                                    <td><strong>JP {{ $slot['number'] }}</strong></td>
                                    <td>{{ $slot['start'] }}</td>
                                    <td>{{ $slot['end'] }}</td>
                                    <td>
                                        @if($slot['number'] == 3)
                                            <span class="badge badge-warning" style="font-weight: 400;"><i class="fas fa-utensils"></i> Makan Bergizi Gratis (09:55 - 10:35)</span>
                                        @elseif($slot['number'] == 5)
                                            <span class="badge badge-warning" style="font-weight: 400;"><i class="fas fa-mosque"></i> Sholat Dhuhur & Istirahat (11:45 - 13:00)</span>
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

            <!-- TAB SELASA-KAMIS -->
            <div id="tab-selasa" class="day-tab-content" style="display:none;">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>JP / Kegiatan</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($setting->getTimeSlotsForDay('Selasa') as $slot)
                                <tr>
                                    <td><strong>JP {{ $slot['number'] }}</strong></td>
                                    <td>{{ $slot['start'] }}</td>
                                    <td>{{ $slot['end'] }}</td>
                                    <td>
                                        @if($slot['number'] == 5)
                                            <span class="badge badge-warning" style="font-weight: 400;"><i class="fas fa-utensils"></i> Makan Bergizi Gratis (09:55 - 10:35)</span>
                                        @elseif($slot['number'] == 7)
                                            <span class="badge badge-warning" style="font-weight: 400;"><i class="fas fa-mosque"></i> Sholat Dhuhur & Istirahat (11:45 - 13:00)</span>
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

            <!-- TAB JUMAT -->
            <div id="tab-jumat" class="day-tab-content" style="display:none;">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>JP / Kegiatan</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: rgba(59,130,246,0.05);">
                                <td><span class="badge badge-info">Kegiatan</span></td>
                                <td><strong>07:00</strong></td>
                                <td><strong>08:20</strong></td>
                                <td style="color:#1e3a8a; font-weight: 500;">PEMBIASAAN / KAJIAN ISLAMI</td>
                            </tr>
                            @foreach($setting->getTimeSlotsForDay('Jumat') as $slot)
                                <tr>
                                    <td><strong>JP {{ $slot['number'] }}</strong></td>
                                    <td>{{ $slot['start'] }}</td>
                                    <td>{{ $slot['end'] }}</td>
                                    <td>
                                        @if($slot['number'] == 2)
                                            <span class="badge badge-warning" style="font-weight: 400;"><i class="fas fa-utensils"></i> Makan Bergizi Gratis (09:40 - 10:20)</span>
                                        @elseif($slot['number'] == 4)
                                            <span class="badge badge-warning" style="font-weight: 400;"><i class="fas fa-mosque"></i> Sholat Jumat (11:55 - 13:00)</span>
                                        @else
                                            <span style="color:#94a3b8;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background: rgba(245,158,11,0.05);">
                                <td><span class="badge badge-warning">Kegiatan</span></td>
                                <td><strong>13:00</strong></td>
                                <td><strong>16:00</strong></td>
                                <td style="color:#b45309; font-weight: 500;">EKSTRAKURIKULER</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
window.switchDayTab = function(day) {
    // Hide all tab contents
    document.querySelectorAll('.day-tab-content').forEach(el => el.style.display = 'none');
    
    // Show target tab content
    document.getElementById('tab-' + day).style.display = 'block';
    
    // Update button styles
    document.querySelectorAll('.day-tab-btn').forEach(btn => {
        if (btn.getAttribute('data-day') === day) {
            btn.classList.remove('btn-outline');
            btn.classList.add('btn-primary');
        } else {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline');
        }
    });
}
</script>
@endsection
@endsection

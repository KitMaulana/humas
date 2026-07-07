@extends('layouts.web')

@section('title', 'Beranda - SMAN 1 Ciruas')

@section('styles')
<style>
/* ── KBM GRID SECTION ────────────────────────── */
.kbm-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2440 100%);
    padding: 48px 0 56px;
    position: relative;
    overflow: hidden;
}
.kbm-section::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(circle at 30% 40%, rgba(59,130,246,0.06) 0%, transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(245,158,11,0.05) 0%, transparent 50%);
    pointer-events: none;
}
.kbm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}
.kbm-title-area {
    display: flex;
    align-items: center;
    gap: 14px;
}
.kbm-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(239,68,68,0.15);
    border: 1px solid rgba(239,68,68,0.3);
    color: #fca5a5;
    padding: 6px 14px;
    border-radius: 24px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.kbm-live-badge .dot {
    width: 8px; height: 8px;
    background: #ef4444;
    border-radius: 50%;
    animation: pulse-kbm 1.4s ease-in-out infinite;
}
@keyframes pulse-kbm {
    0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
    50% { opacity: 0.7; transform: scale(1.3); box-shadow: 0 0 0 6px rgba(239,68,68,0); }
}
.kbm-title {
    color: white;
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: -0.01em;
}
.kbm-day-info {
    color: rgba(255,255,255,0.5);
    font-size: 13px;
    font-weight: 500;
}
.kbm-day-info strong {
    color: #f59e0b;
    font-weight: 700;
}

/* ── GRADE TABS ──────────────────────────────── */
.grade-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 20px;
}
.grade-tab {
    padding: 10px 28px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.6);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s;
    font-family: inherit;
    letter-spacing: 0.03em;
}
.grade-tab:first-child { border-radius: 8px 0 0 8px; }
.grade-tab:last-child { border-radius: 0 8px 8px 0; }
.grade-tab.active, .grade-tab:hover {
    background: rgba(59,130,246,0.2);
    border-color: rgba(59,130,246,0.4);
    color: #93c5fd;
}
.grade-tab.active {
    background: rgba(59,130,246,0.3);
    color: white;
}

/* ── GRID PANEL ──────────────────────────────── */
.grade-panel {
    display: none;
}
.grade-panel.active {
    display: block;
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.kbm-grid-wrap {
    overflow-x: auto;
    border-radius: 12px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(8px);
}
.kbm-grid {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    min-width: 600px;
}
.kbm-grid thead th {
    background: rgba(15,23,42,0.8);
    color: rgba(255,255,255,0.7);
    padding: 12px 16px;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    white-space: nowrap;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    text-align: left;
}
.kbm-grid tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    vertical-align: middle;
    text-align: left;
    color: rgba(255,255,255,0.8);
}
.kbm-grid tbody tr {
    transition: background .15s;
}
.kbm-grid tbody tr:hover {
    background: rgba(255,255,255,0.02);
}
.td-time {
    font-weight: 700;
    color: #38bdf8;
    white-space: nowrap;
}
.td-mapel {
    font-weight: 600;
    color: white;
}
.td-guru {
    color: rgba(255,255,255,0.6);
}
.td-kelas {
    font-size: 11px;
    font-weight: 700;
    background: rgba(255,255,255,0.1);
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    display: inline-block;
    white-space: nowrap;
}
.row-live {
    background: rgba(239,68,68,0.06) !important;
}
.row-live:hover {
    background: rgba(239,68,68,0.09) !important;
}
.badge-live {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(239,68,68,0.15); color: #f87171;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
    border: 1px solid rgba(239,68,68,0.3);
}
.badge-live::before {
    content: ''; width: 6px; height: 6px;
    background: #ef4444; border-radius: 50%;
    animation: pulse-dot-home 1.2s infinite;
}
.badge-break {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(46, 204, 113, 0.15) !important;
    color: #2ecc71 !important;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
    border: 1px solid rgba(46, 204, 113, 0.3) !important;
}
.badge-break::before {
    content: ''; width: 6px; height: 6px;
    background: #2ecc71 !important; border-radius: 50%;
    animation: pulse-dot-home 1.2s infinite;
}
@keyframes pulse-dot-home {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}
.badge-upcoming {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(59,130,246,0.15); color: #93c5fd;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    border: 1px solid rgba(59,130,246,0.3);
}
.kbm-empty {
    text-align: center;
    padding: 48px 20px;
    color: rgba(255,255,255,0.4);
}
.kbm-empty i {
    font-size: 2rem;
    margin-bottom: 12px;
    display: block;
    color: rgba(255,255,255,0.15);
}
.kbm-empty p {
    font-size: 14px;
    margin: 0;
}
@media (max-width: 768px) {
    .kbm-header { flex-direction: column; align-items: flex-start; }
    .grade-tab { padding: 8px 16px; font-size: 12px; }
    .kbm-title { font-size: 1.1rem; }
}
</style>
@endsection

@section('content')
@php
    $slides = [];
    if ($profile) {
        for ($i = 1; $i <= 5; $i++) {
            $colName = "hero_image_$i";
            if ($profile->$colName) {
                $slides[] = asset('storage/' . $profile->$colName);
            }
        }
    }
@endphp

<section class="hero {{ count($slides) > 0 ? 'has-slides' : '' }}">
    @if(count($slides) > 0)
        <div class="hero-slides">
            @foreach($slides as $index => $slideUrl)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $slideUrl }}');"></div>
            @endforeach
        </div>
        <div class="hero-overlay"></div>
    @endif
    <div class="container" style="position: relative; z-index: 5;">
        <h2 style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">Selamat Datang di Portal Informasi SMAN 1 Ciruas</h2>
        <p style="font-weight: bold; color: white; margin-bottom: 20px; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">Dikelola oleh TIM HUMAS SMAN 1 CIRUAS</p>
        <p style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">{{ $profile->vision ?? 'Mewujudkan generasi yang bertaqwa, cerdas, terampil, dan berwawasan lingkungan.' }}</p>
        <div class="hero-buttons">
            <a href="{{ route('schedule') }}" class="hero-btn btn-jadwal"><i class="fas fa-calendar-alt"></i> Cek Jadwal Pelajaran</a>
            <a href="{{ route('statistics') }}" class="hero-btn btn-statistik"><i class="fas fa-chart-bar"></i> Statistik</a>
            <a href="{{ route('achievements') }}" class="hero-btn btn-prestasi"><i class="fas fa-trophy"></i> Prestasi</a>
            <a href="{{ route('facilities') }}" class="hero-btn btn-fasilitas"><i class="fas fa-school"></i> Fasilitas</a>
        </div>
    </div>
</section>

{{-- ══════ KBM SEDANG BERLANGSUNG ══════ --}}
<div class="kbm-section">
    <div class="container">
        <div class="kbm-header">
            <div class="kbm-title-area">
                <div class="kbm-live-badge"><span class="dot"></span> LIVE</div>
                <div>
                    <div class="kbm-title">KBM Sedang Berlangsung</div>
                    <div class="kbm-day-info">Hari ini: <strong>{{ $currentDay }}</strong> — Durasi per JP: <strong>{{ $lessonSetting->lesson_duration }} menit</strong></div>
                </div>
            </div>
        </div>

        @php
            $grades = ['X', 'XI', 'XII'];
            $hasAnySchedule = count($schedulesByGrade['X']) > 0 || count($schedulesByGrade['XI']) > 0 || count($schedulesByGrade['XII']) > 0;
        @endphp

        @if(!$hasAnySchedule || $currentDay === 'Minggu')
            <div class="kbm-empty">
                <i class="fas fa-moon"></i>
                <p>{{ $currentDay === 'Minggu' ? 'Hari Minggu — tidak ada jadwal pelajaran.' : 'Tidak ada kegiatan Belajar Mengajar (KBM) yang berlangsung saat ini.' }}</p>
            </div>
        @else
            {{-- Grade Tabs --}}
            <div class="grade-tabs">
                @foreach($grades as $i => $grade)
                    <button class="grade-tab {{ $i === 0 ? 'active' : '' }}" onclick="switchGrade('{{ $grade }}')" data-grade="{{ $grade }}">
                        Kelas {{ $grade }}
                    </button>
                @endforeach
            </div>

            {{-- Grade Panels --}}
            @foreach($grades as $i => $grade)
                <div class="grade-panel {{ $i === 0 ? 'active' : '' }}" id="panel-{{ $grade }}">
                    @if(count($schedulesByGrade[$grade]) > 0)
                        <div class="kbm-grid-wrap">
                            <table class="kbm-grid">
                                <thead>
                                    <tr>
                                        <th>JP</th>
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedulesByGrade[$grade] as $sch)
                                         @php
                                             $rowStyle = '';
                                             $textColor = '#fbbf24';
                                             $icon = 'fa-bullhorn';
                                             $badgeText = 'AGENDA BERSAMA';
                                             $badgeClass = 'badge-live';
                                             if ($sch->is_break) {
                                                 $rowStyle = 'background: rgba(46, 204, 113, 0.08) !important;';
                                                 $textColor = '#2ecc71';
                                                 $icon = 'fa-coffee';
                                                 $badgeText = 'ISTIRAHAT';
                                                 $badgeClass = 'badge-break';
                                             } elseif ($sch->title) {
                                                 $rowStyle = 'background: rgba(245, 158, 11, 0.08) !important;';
                                                 $textColor = '#fbbf24';
                                                 $icon = 'fa-bullhorn';
                                                 $badgeText = 'AGENDA BERSAMA';
                                                 $badgeClass = 'badge-live';
                                             }
                                         @endphp
                                        <tr class="row-live" style="{{ $rowStyle }}">
                                            <td style="font-weight: 800; color: {{ $textColor }}; white-space: nowrap;">JP {{ $sch->lesson_number ?? '—' }}</td>
                                            <td class="td-time">{{ substr($sch->start_time, 0, 5) }}–{{ substr($sch->end_time, 0, 5) }}</td>
                                            @if($sch->title)
                                                <td colspan="3" style="font-weight: bold; color: {{ $textColor }}; text-align: left; padding-left: 20px;">
                                                    <i class="fas {{ $icon }}" style="margin-right: 8px; color: {{ $textColor }};"></i> {{ $sch->title }} (Semua Kelas)
                                                </td>
                                            @else
                                                <td class="td-mapel">{{ $sch->subject->name ?? '—' }}</td>
                                                <td class="td-guru">{{ $sch->teacher->name ?? '—' }}</td>
                                                <td><span class="td-kelas">{{ $sch->schoolClass->name ?? '—' }}</span></td>
                                            @endif
                                            <td>
                                                <span class="{{ $badgeClass }}">{{ $badgeText }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="kbm-empty">
                            <i class="fas fa-chalkboard"></i>
                            <p>Tidak ada KBM yang sedang berlangsung untuk tingkat {{ $grade }} saat ini.</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="container">
    <div class="stats">
        <div class="stat-card">
            <h3 id="student-count">{{ $studentCount }}</h3>
            <p>Total Siswa</p>
        </div>
        <div class="stat-card">
            <h3 id="teacher-count">{{ $teacherCount }}</h3>
            <p>Guru & Staf</p>
        </div>
        <div class="stat-card">
            <h3 id="class-count">{{ $classCount }}</h3>
            <p>Rombongan Belajar</p>
        </div>
        <div class="stat-card">
            <h3 id="achievement-count">{{ $achievementCount }}</h3>
            <p>Prestasi Tercatat</p>
        </div>
    </div>
</div>

<section style="background-color: var(--light-blue);">
    <div class="container">
        <div class="section-title">
            <h2>Visi & Misi</h2>
        </div>
        <div class="grid-2">
            <div style="background-color: var(--white); padding: 40px; border-radius: 15px; border-left: 5px solid var(--primary-blue);">
                <h3 style="color: var(--primary-blue); margin-bottom: 15px;">Visi</h3>
                <p style="font-style: italic; font-size: 1.1rem;">"{{ $profile->vision ?? 'Menjadi pusat keunggulan pendidikan yang membentuk insan cerdas, religius, dan berkarakter.' }}"</p>
            </div>
            <div style="background-color: var(--white); padding: 40px; border-radius: 15px; border-left: 5px solid var(--primary-orange);">
                <h3 style="color: var(--primary-orange); margin-bottom: 15px;">Misi</h3>
                <div style="color: #666;">
                    {!! nl2br(e($profile->mission ?? "- Menyelenggarakan pendidikan berkualitas\n- Mengembangkan potensi minat dan bakat\n- Membentuk karakter disiplin dan tanggung jawab")) !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Grade tab switching
    function switchGrade(grade) {
        document.querySelectorAll('.grade-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.grade-panel').forEach(p => p.classList.remove('active'));
        document.querySelector(`.grade-tab[data-grade="${grade}"]`).classList.add('active');
        document.getElementById(`panel-${grade}`).classList.add('active');
    }

    // Counter animation
    function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (end - start) + start);
            if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', () => {
        animateValue(document.getElementById('student-count'), 0, {{ $studentCount }}, 2000);
        animateValue(document.getElementById('teacher-count'), 0, {{ $teacherCount }}, 2000);
        animateValue(document.getElementById('class-count'), 0, {{ $classCount }}, 2000);
        animateValue(document.getElementById('achievement-count'), 0, {{ $achievementCount }}, 2000);

        // Hero Background Slideshow
        const slides = document.querySelectorAll('.hero-slide');
        if (slides.length > 1) {
            let currentSlide = 0;
            setInterval(() => {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }, 5000);
        }
    });

    // Auto-refresh KBM grid every 60 seconds
    setInterval(() => { location.reload(); }, 60000);
</script>
@endsection

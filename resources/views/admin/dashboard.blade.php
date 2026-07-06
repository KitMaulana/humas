@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Selamat Datang, {{ auth()->user()->name }} 👋</h1>
        <p>Ringkasan Sistem Informasi SMAN 1 Ciruas</p>
    </div>
</div>

{{-- 3 MAIN METRICS CARDS (Gradient & Detailed) --}}
<div class="main-metrics-grid">
    {{-- CARD 1: BLUE (GURU & STAF) --}}
    <div class="metric-card metric-blue">
        <div class="metric-card-header">
            <div class="metric-info">
                <span class="metric-title">TOTAL GURU & STAF</span>
                <h2 class="metric-value">{{ number_format($teacherCount + $staffCount) }} <span class="unit">Orang</span></h2>
            </div>
            <div class="metric-icon-wrap">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
        <div class="metric-card-body">
            <span class="metric-subtitle">Distribusi Pendidik</span>
            <ul class="metric-list">
                <li>
                    <span class="bullet"></span> Guru PNS: <strong>{{ $pnsCount }}</strong>
                </li>
                <li>
                    <span class="bullet"></span> Honorer / Non-PNS: <strong>{{ $honorerCount + $otherTeacherStatusCount }}</strong>
                </li>
                <li>
                    <span class="bullet"></span> Tenaga Kependidikan (Staf): <strong>{{ $staffCount }}</strong>
                </li>
            </ul>
        </div>
    </div>

    {{-- CARD 2: GREEN (SISWA) --}}
    <div class="metric-card metric-green">
        <div class="metric-card-header">
            <div class="metric-info">
                <span class="metric-title">TOTAL SISWA</span>
                <h2 class="metric-value">{{ number_format($totalStudentCount) }} <span class="unit">Siswa</span></h2>
            </div>
            <div class="metric-icon-wrap">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="metric-card-body">
            <span class="metric-subtitle">Statistik Gender & Rombel</span>
            <ul class="metric-list">
                <li>
                    <span class="bullet"></span> Siswa Laki-laki: <strong>{{ number_format($maleStudentCount) }}</strong>
                </li>
                <li>
                    <span class="bullet"></span> Siswa Perempuan: <strong>{{ number_format($femaleStudentCount) }}</strong>
                </li>
                <li>
                    <span class="bullet"></span> Jumlah Kelas (Rombel): <strong>{{ $classCount }} Kelas</strong>
                </li>
            </ul>
        </div>
    </div>

    {{-- CARD 3: ORANGE (PRESTASI & KEMITRAAN) --}}
    <div class="metric-card metric-orange">
        <div class="metric-card-header">
            <div class="metric-info">
                <span class="metric-title">PRESTASI & KERJASAMA</span>
                <h2 class="metric-value">{{ number_format($achievementCount) }} <span class="unit">Prestasi</span></h2>
            </div>
            <div class="metric-icon-wrap">
                <i class="fas fa-award"></i>
            </div>
        </div>
        <div class="metric-card-body">
            <span class="metric-subtitle">Rekam Jejak Sekolah</span>
            <ul class="metric-list">
                <li>
                    <span class="bullet"></span> Tingkat Nasional: <strong>{{ $nationalAchievementCount }}</strong>
                </li>
                <li>
                    <span class="bullet"></span> Tingkat Provinsi: <strong>{{ $provincialAchievementCount }}</strong>
                </li>
                <li>
                    <span class="bullet"></span> Mitra Instansi / DUDI: <strong>{{ $partnershipCount }} Kerjasama</strong>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- SECONDARY METRICS GRID --}}
<h3 class="section-title">Data Pendukung Lainnya</h3>
<div class="secondary-metrics-grid">
    <div class="sub-stat-card">
        <div class="sub-stat-icon text-pink"><i class="fas fa-book-open"></i></div>
        <div class="sub-stat-info">
            <div class="sub-stat-value">{{ $subjectCount }}</div>
            <div class="sub-stat-label">Mata Pelajaran</div>
        </div>
    </div>
    <div class="sub-stat-card">
        <div class="sub-stat-icon text-cyan"><i class="fas fa-calendar-alt"></i></div>
        <div class="sub-stat-info">
            <div class="sub-stat-value">{{ $scheduleCount }}</div>
            <div class="sub-stat-label">Jadwal Pelajaran</div>
        </div>
    </div>
    <div class="sub-stat-card">
        <div class="sub-stat-icon text-indigo"><i class="fas fa-building"></i></div>
        <div class="sub-stat-info">
            <div class="sub-stat-value">{{ $facilityCount }}</div>
            <div class="sub-stat-label">Fasilitas Sekolah</div>
        </div>
    </div>
    <div class="sub-stat-card">
        <div class="sub-stat-icon text-purple"><i class="fas fa-sitemap"></i></div>
        <div class="sub-stat-info">
            <div class="sub-stat-value">{{ $orgStructureCount }}</div>
            <div class="sub-stat-label">Struktur Organisasi</div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>Aksi Cepat</h3>
    </div>
    <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-outline"><i class="fas fa-plus"></i> Tambah Guru</a>
        <a href="{{ route('admin.school-classes.create') }}" class="btn btn-outline"><i class="fas fa-plus"></i> Tambah Kelas</a>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-outline"><i class="fas fa-plus"></i> Tambah Jadwal</a>
        <a href="{{ route('admin.achievements.create') }}" class="btn btn-outline"><i class="fas fa-plus"></i> Tambah Prestasi</a>
        <a href="{{ route('admin.school-profile.edit') }}" class="btn btn-outline"><i class="fas fa-edit"></i> Edit Profil Sekolah</a>
    </div>
</div>
@endsection

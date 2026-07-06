@extends('layouts.web')

@section('title', 'Data & Statistik - SMAN 1 Ciruas')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .card {
        background-color: var(--white);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .summary-item {
        background-color: var(--white);
        padding: 20px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')
<div style="background-color: var(--primary-orange); color: white; padding: 60px 0;">
    <div class="container">
        <h1>Data & Statistik</h1>
        <p>Ringkasan data siswa, alumni, dan pencapaian SMAN 1 Ciruas dalam angka.</p>
    </div>
</div>

<div class="container" style="margin-top: -30px;">
    <!-- Filter Card -->
    <div class="card" style="margin-bottom: 30px; border-top: 4px solid var(--primary-orange);">
        <form action="{{ route('statistics') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem;">Thn Pelajaran</label>
                <select name="academic_year" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">Semua Tahun</option>
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay }}" {{ request('academic_year') == $ay ? 'selected' : '' }}>{{ $ay }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem;">Tingkatan</label>
                <select name="grade" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">Semua Tingkatan</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>Kelas {{ $grade }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem;">Kelas Spesifik</label>
                <select name="class_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem;">Jenis Kelamin</label>
                <select name="gender" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="all" {{ request('gender') == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; background-color: var(--primary-blue); color: white; border: none; padding: 10px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('statistics') }}" style="background-color: #eee; color: #555; padding: 10px; border-radius: 8px; text-decoration: none; text-align: center;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="stats-summary">
        @php
            $totalMale = $stats->sum('male_count');
            $totalFemale = $stats->sum('female_count');
            $totalStudents = $totalMale + $totalFemale;
            $displayTotal = request('gender') == 'male' ? $totalMale : (request('gender') == 'female' ? $totalFemale : $totalStudents);
        @endphp
        <div class="summary-item">
            <div class="icon-box" style="background-color: var(--light-blue); color: var(--primary-blue);">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p style="color: var(--gray); font-size: 0.9rem;">Filter: {{ strtoupper(request('gender', 'Total')) }}</p>
                <h4 style="font-size: 1.2rem;">{{ number_format($displayTotal) }} Siswa</h4>
            </div>
        </div>
        <div class="summary-item">
            <div class="icon-box" style="background-color: var(--light-orange); color: var(--primary-orange);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <p style="color: var(--gray); font-size: 0.9rem;">Laki-laki</p>
                <h4 style="font-size: 1.2rem;">{{ number_format($totalMale) }}</h4>
            </div>
        </div>
        <div class="summary-item">
            <div class="icon-box" style="background-color: #e0f2f1; color: #00897b;">
                <i class="fas fa-user-nurse"></i>
            </div>
            <div>
                <p style="color: var(--gray); font-size: 0.9rem;">Perempuan</p>
                <h4 style="font-size: 1.2rem;">{{ number_format($totalFemale) }}</h4>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 20px; color: var(--primary-blue);">Perkembangan Jumlah Siswa</h3>
            <div class="chart-container">
                <canvas id="studentChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h3 style="margin-bottom: 20px; color: var(--primary-blue);">Sebaran Per Jurusan</h3>
            <div class="chart-container">
                <canvas id="majorChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 20px; color: var(--primary-blue);">Statistik Karir Alumni</h3>
        <div class="chart-container" style="height: 400px;">
            <canvas id="alumniChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Student Growth Chart - DYNAMIC
    const studentCtx = document.getElementById('studentChart').getContext('2d');
    new Chart(studentCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($growthLabels) !!},
            datasets: [{
                label: 'Jumlah Siswa',
                data: {!! json_encode($growthValues) !!},
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Major Distribution Chart - DYNAMIC
    const majorCtx = document.getElementById('majorChart').getContext('2d');
    new Chart(majorCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($majorLabels) !!},
            datasets: [{
                data: {!! json_encode($majorValues) !!},
                backgroundColor: ['#3498db', '#f39c12', '#9b59b6', '#7f8c8d']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Alumni Career Chart (Still uses dummy for now or basic aggregation)
    const alumniCtx = document.getElementById('alumniChart').getContext('2d');
    new Chart(alumniCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($alumni->pluck('year')->unique()->values()) !!},
            datasets: [
                {
                    label: 'Kuliah',
                    data: {!! json_encode($alumni->pluck('college_count')->values()) !!},
                    backgroundColor: '#3498db'
                },
                {
                    label: 'Kerja',
                    data: {!! json_encode($alumni->pluck('work_count')->values()) !!},
                    backgroundColor: '#f39c12'
                },
                {
                    label: 'Wirausaha',
                    data: {!! json_encode($alumni->pluck('entrepreneur_count')->values()) !!},
                    backgroundColor: '#2ecc71'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

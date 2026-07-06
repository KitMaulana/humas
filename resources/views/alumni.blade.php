@extends('layouts.web')

@section('title', 'Kerjasama & Alumni - SMAN 1 Ciruas')

@section('styles')
<style>
    .alumni-card {
        background-color: var(--white);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-top: 5px solid var(--primary-orange);
    }
    .alumni-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    .career-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .career-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div style="background-color: var(--dark-blue); color: white; padding: 60px 0;">
    <div class="container">
        <h1>Kerjasama & Alumni</h1>
        <p>Jejaring alumni SMAN 1 Ciruas yang tersebar di berbagai sektor dan instansi.</p>
    </div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <div class="section-title">
        <h2>Statistik Karir Alumni</h2>
    </div>

    @if($alumni->isEmpty())
        <div class="alumni-grid">
            @php
                $sample = [
                    ['year' => '2023', 'college' => 210, 'work' => 45, 'ent' => 15],
                    ['year' => '2022', 'college' => 195, 'work' => 50, 'ent' => 10],
                    ['year' => '2021', 'college' => 180, 'work' => 55, 'ent' => 12],
                ];
            @endphp
            @foreach($sample as $s)
                <div class="alumni-card">
                    <h3 style="margin-bottom: 20px; color: var(--primary-blue);">Lulusan Tahun {{ $s['year'] }}</h3>
                    <div class="career-item">
                        <span>Kuliah</span>
                        <span style="font-weight: bold; color: var(--primary-blue);">{{ $s['college'] }} Siswa</span>
                    </div>
                    <div class="career-item">
                        <span>Kerja</span>
                        <span style="font-weight: bold; color: var(--primary-orange);">{{ $s['work'] }} Siswa</span>
                    </div>
                    <div class="career-item">
                        <span>Wirausaha</span>
                        <span style="font-weight: bold; color: var(--success);">{{ $s['ent'] }} Siswa</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alumni-grid">
            @foreach($alumni as $a)
                <div class="alumni-card">
                    <h3 style="margin-bottom: 20px; color: var(--primary-blue);">Lulusan Tahun {{ $a->year }}</h3>
                    <div class="career-item">
                        <span>Kuliah</span>
                        <span style="font-weight: bold; color: var(--primary-blue);">{{ $a->college_count }} Siswa</span>
                    </div>
                    <div class="career-item">
                        <span>Kerja</span>
                        <span style="font-weight: bold; color: var(--primary-orange);">{{ $a->work_count }} Siswa</span>
                    </div>
                    <div class="career-item">
                        <span>Wirausaha</span>
                        <span style="font-weight: bold; color: var(--success);">{{ $a->entrepreneur_count }} Siswa</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="card" style="margin-top: 50px; background-color: var(--light-orange); padding: 40px; border-radius: 15px;">
        <div style="display: flex; gap: 40px; align-items: center;">
            <div style="font-size: 3rem; color: var(--primary-orange);"><i class="fas fa-handshake"></i></div>
            <div>
                <h3 style="color: var(--dark-blue); margin-bottom: 10px;">Kerjasama Instansi</h3>
                <p>Kami menjalin kerjasama dengan berbagai Perguruan Tinggi Negeri (PTN), Swasta, serta dunia industri untuk memastikan kualitas pendidikan dan masa depan siswa.</p>
            </div>
        </div>
    </div>
</div>
@endsection

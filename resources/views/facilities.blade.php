@extends('layouts.web')

@section('title', 'Sarana & Prasarana - SMAN 1 Ciruas')

@section('styles')
<style>
    .facility-card {
        background-color: var(--white);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.3s;
    }
    .facility-card:hover {
        transform: scale(1.02);
    }
    .f-icon {
        width: 60px;
        height: 60px;
        background-color: var(--light-blue);
        color: var(--primary-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')
<div style="background-color: var(--primary-blue); color: white; padding: 60px 0;">
    <div class="container">
        <h1>Sarana & Prasarana</h1>
        <p>Fasilitas pendukung kegiatan belajar mengajar yang modern dan lengkap.</p>
    </div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    @if($facilities->isEmpty())
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @php
                $defaults = [
                    ['icon' => 'fas fa-chalkboard-teacher', 'name' => 'Ruang Kelas', 'count' => '36 Ruang'],
                    ['icon' => 'fas fa-flask', 'name' => 'Laboratorium IPA', 'count' => '3 Lab'],
                    ['icon' => 'fas fa-laptop-code', 'name' => 'Laboratorium Komputer', 'count' => '2 Lab'],
                    ['icon' => 'fas fa-book', 'name' => 'Perpustakaan', 'count' => '1 Ruang'],
                    ['icon' => 'fas fa-basketball-ball', 'name' => 'Lap. Olahraga', 'count' => '2 Area'],
                    ['icon' => 'fas fa-mosque', 'name' => 'Masjid', 'count' => '1 Bangunan'],
                ];
            @endphp
            @foreach($defaults as $f)
                <div class="facility-card">
                    <div class="f-icon"><i class="{{ $f['icon'] }}"></i></div>
                    <div>
                        <h3 style="font-size: 1.1rem; color: var(--dark-blue);">{{ $f['name'] }}</h3>
                        <p style="color: var(--gray); font-size: 0.9rem;">{{ $f['count'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($facilities as $facility)
                <div class="facility-card">
                    <div class="f-icon"><i class="fas fa-building"></i></div>
                    <div>
                        <h3 style="font-size: 1.1rem; color: var(--dark-blue);">{{ $facility->name }}</h3>
                        <p style="color: var(--gray); font-size: 0.9rem;">{{ $facility->count }} Ruang - Kondisi: {{ $facility->condition }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

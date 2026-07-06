@extends('layouts.web')

@section('title', 'Prestasi - SMAN 1 Ciruas')

@section('styles')
<style>
    .achievement-item {
        background-color: var(--white);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s;
        display: flex;
        flex-direction: column;
    }
    .achievement-item:hover {
        transform: translateY(-10px);
    }
    .achievement-img {
        width: 100%;
        height: 200px;
        background-size: cover;
        background-position: center;
    }
    .achievement-content {
        padding: 20px;
        flex-grow: 1;
    }
    .badge-year {
        background-color: var(--primary-orange);
        color: white;
        padding: 2px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .badge-level {
        background-color: var(--primary-blue);
        color: white;
        padding: 2px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div style="background-color: var(--dark-blue); color: white; padding: 60px 0;">
    <div class="container">
        <h1>Prestasi Sekolah</h1>
        <p>Kebanggaan komunitas SMAN 1 Ciruas dari tingkat lokal hingga internasional.</p>
    </div>
</div>

<div class="container" style="margin-top: 40px;">
    @if($achievements->isEmpty())
        <div style="text-align: center; padding: 100px 0;">
            <i class="fas fa-trophy" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
            <p style="color: var(--gray);">Belum ada data prestasi yang tercatat.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 50px;">
            @foreach($achievements as $achievement)
                <div class="achievement-item">
                    <div class="achievement-img" style="background-image: url('{{ $achievement->photo_path ? asset('storage/'.$achievement->photo_path) : 'https://via.placeholder.com/400x300?text=Prestasi' }}')"></div>
                    <div class="achievement-content">
                        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <span class="badge-year">{{ $achievement->year }}</span>
                            <span class="badge-level">{{ $achievement->level ?? 'Nasional' }}</span>
                        </div>
                        <h3 style="margin-bottom: 10px; color: var(--dark-blue); line-height: 1.3;">{{ $achievement->title }}</h3>
                        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">Kategori: {{ $achievement->category }}</p>
                        <p style="font-size: 0.95rem; color: #555;">{{ Str::limit($achievement->description, 100) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

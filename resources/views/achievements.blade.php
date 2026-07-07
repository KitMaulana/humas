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
    .btn-filter {
        padding: 10px 22px;
        border-radius: 30px;
        border: 1.5px solid #CBD5E0;
        background: white;
        color: #4A5568;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        font-family: inherit;
    }
    .btn-filter.active, .btn-filter:hover {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.25);
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
    <div class="filter-buttons" style="display: flex; justify-content: center; gap: 12px; margin-bottom: 35px; flex-wrap: wrap;">
        <button class="btn-filter active" data-filter="all">Semua Prestasi</button>
        <button class="btn-filter" data-filter="Siswa"><i class="fas fa-user-graduate"></i> Prestasi Siswa</button>
        <button class="btn-filter" data-filter="Guru"><i class="fas fa-chalkboard-teacher"></i> Prestasi Guru</button>
        <button class="btn-filter" data-filter="Sekolah"><i class="fas fa-school"></i> Prestasi Sekolah</button>
    </div>

    @if($achievements->isEmpty())
        <div style="text-align: center; padding: 100px 0;">
            <i class="fas fa-trophy" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
            <p style="color: var(--gray);">Belum ada data prestasi yang tercatat.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 50px;">
            @foreach($achievements as $achievement)
                <div class="achievement-item" data-category="{{ $achievement->category }}">
                    <div class="achievement-img" style="background-image: url('{{ $achievement->photo_path ? asset('storage/'.$achievement->photo_path) : 'https://via.placeholder.com/400x300?text=Prestasi' }}')"></div>
                    <div class="achievement-content">
                        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <span class="badge-year">{{ $achievement->year }}</span>
                            <span class="badge-level">{{ $achievement->level ?? 'Nasional' }}</span>
                        </div>
                        <h3 style="margin-bottom: 10px; color: var(--dark-blue); line-height: 1.3;">{{ $achievement->title }}</h3>
                        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">Kategori: {{ $achievement->category }}</p>
                        
                        @if($achievement->names)
                            <div style="font-size: 0.85rem; color: #475569; background: #f1f5f9; border-left: 3px solid var(--primary-blue); padding: 8px 12px; margin-bottom: 15px; border-radius: 0 6px 6px 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user-friends" style="color: var(--primary-blue);"></i>
                                <span>Penerima: <strong>{{ $achievement->names }}</strong></span>
                            </div>
                        @endif

                        <p style="font-size: 0.95rem; color: #555;">{{ Str::limit($achievement->description, 100) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="empty-state-filter" style="display: none; text-align: center; padding: 60px 0; margin-bottom: 50px;">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
            <p style="color: var(--gray); font-weight: 500;">Tidak ada prestasi untuk kategori ini.</p>
        </div>
    @endif
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn-filter');
    const items = document.querySelectorAll('.achievement-item');
    const emptyState = document.getElementById('empty-state-filter');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            let visibleCount = 0;

            items.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endsection
@endsection

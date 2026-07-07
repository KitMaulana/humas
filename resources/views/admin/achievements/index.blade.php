@extends('layouts.admin')
@section('page-title', 'Prestasi')
@section('content')
<div class="page-header">
    <div>
        <h1>Prestasi Sekolah</h1>
        <p>Kelola pencapaian siswa, guru, dan institusi</p>
    </div>
    <a href="{{ route('admin.achievements.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Prestasi</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.achievements.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul prestasi...">
            </div>
            <select name="category" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="Siswa" {{ request('category') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="Guru" {{ request('category') == 'Guru' ? 'selected' : '' }}>Guru</option>
                <option value="Sekolah" {{ request('category') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
            </select>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.achievements.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 65px;">Foto</th>
                        <th>Judul Prestasi</th>
                        <th>Kategori</th>
                        <th>Tingkat</th>
                        <th>Tahun</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                @if($item->photo_path)
                                    <img src="{{ asset('storage/' . $item->photo_path) }}" class="img-thumb" alt="Foto">
                                @else
                                    <div class="img-thumb" style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;"><i class="fas fa-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->title }}</strong>
                                @if($item->names)
                                    <div style="font-size: 11.5px; color: var(--gray); margin-top: 4px;"><i class="fas fa-user-friends" style="margin-right: 4px;"></i> {{ $item->names }}</div>
                                @endif
                            </td>
                            <td><span class="badge badge-primary">{{ $item->category }}</span></td>
                            <td><span class="badge badge-info">{{ $item->level ?? '—' }}</span></td>
                            <td><strong>{{ $item->year }}</strong></td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.achievements.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.achievements.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data prestasi ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            <div>Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} data</div>
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('page-title', 'Detail Kampus Alumni')
@section('content')
<div class="page-header">
    <div>
        <h1>Detail Kampus Alumni</h1>
        <p>Kelola rincian penyerapan lulusan di perguruan tinggi secara dinamis</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('admin.alumni-universities.import') }}" class="btn btn-outline"><i class="fas fa-file-import"></i> Import CSV</a>
        <a href="{{ route('admin.alumni-universities.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form action="{{ route('admin.alumni-universities.index') }}" method="GET" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
            <div class="form-group" style="margin-bottom: 0; min-width: 250px;">
                <select name="year" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Angkatan / Tahun</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Angkatan {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('year'))
                <a href="{{ route('admin.alumni-universities.index') }}" style="color: var(--danger); font-size: 13px; font-weight: 600; text-decoration: none;"><i class="fas fa-times"></i> Bersihkan Filter</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 150px;">Tahun Angkatan</th>
                        <th style="width: 100px; text-align: center;">Icon</th>
                        <th>Nama Kampus</th>
                        <th>Kategori</th>
                        <th>Jumlah Siswa</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong>Angkatan {{ $item->year }}</strong></td>
                            <td style="font-size: 20px; text-align: center;">{{ $item->icon }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if($item->category == 'ptn')
                                    <span class="badge badge-info">PTN Favorit</span>
                                @elseif($item->category == 'ptn-lokal')
                                    <span class="badge badge-success">PTN Banten</span>
                                @else
                                    <span class="badge badge-warning">PTS</span>
                                @endif
                            </td>
                            <td><strong>{{ $item->count }} siswa</strong></td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.alumni-universities.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.alumni-universities.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state" style="padding: 40px; text-align: center;">
                                <i class="fas fa-university" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 12px; display: block;"></i>
                                <p style="color: var(--text-secondary);">Tidak ada data rincian kampus alumni ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap" style="margin-top: 20px;">
            <div>Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} data</div>
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

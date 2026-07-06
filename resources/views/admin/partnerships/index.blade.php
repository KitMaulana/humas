@extends('layouts.admin')
@section('page-title', 'Kemitraan')
@section('content')
<div class="page-header">
    <div>
        <h1>Kemitraan & Kerjasama</h1>
        <p>Kelola data mitra kerja sama institusi sekolah</p>
    </div>
    <a href="{{ route('admin.partnerships.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Mitra</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.partnerships.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mitra...">
            </div>
            @if(request('search'))
                <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Mitra</th>
                        <th>Tipe / Jenis Mitra</th>
                        <th>Keterangan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td><span class="badge badge-info">{{ $item->partner_type ?? 'Umum' }}</span></td>
                            <td>{{ Str::limit($item->description, 100) }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.partnerships.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.partnerships.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data kemitraan ditemukan.</p>
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

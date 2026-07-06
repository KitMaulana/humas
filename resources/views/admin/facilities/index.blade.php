@extends('layouts.admin')
@section('page-title', 'Fasilitas')
@section('content')
<div class="page-header">
    <div>
        <h1>Fasilitas Sekolah</h1>
        <p>Kelola sarana prasarana sekolah dan kondisinya</p>
    </div>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Fasilitas</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.facilities.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fasilitas...">
            </div>
            @if(request('search'))
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 65px;">Foto</th>
                        <th>Nama Fasilitas</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
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
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->count }} unit</td>
                            <td>
                                @if($item->condition == 'Baik')
                                    <span class="badge badge-success">Baik</span>
                                @elseif($item->condition == 'Rusak Ringan')
                                    <span class="badge badge-warning">Rusak Ringan</span>
                                @else
                                    <span class="badge badge-danger">Rusak Berat</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.facilities.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.facilities.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data fasilitas ditemukan.</p>
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

@extends('layouts.admin')
@section('page-title', 'Mata Pelajaran')
@section('content')
<div class="page-header">
    <div>
        <h1>Mata Pelajaran</h1>
        <p>Kelola data mata pelajaran kurikulum merdeka</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.subjects.import') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Mapel</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.subjects.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mata pelajaran...">
            </div>
            @if(request('search'))
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Mata Pelajaran</th>
                        <th>Slug</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td><code>{{ $item->slug }}</code></td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.subjects.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.subjects.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada mata pelajaran ditemukan.</p>
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

@extends('layouts.admin')
@section('page-title', 'Data Kelas')
@section('content')
<div class="page-header">
    <div>
        <h1>Data Kelas</h1>
        <p>Kelola tingkatan kelas dan rombongan belajar</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.school-classes.import') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="{{ route('admin.school-classes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kelas</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.school-classes.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama rombel...">
            </div>
            <select name="grade" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Tingkat</option>
                <option value="X" {{ request('grade') == 'X' ? 'selected' : '' }}>Kelas X</option>
                <option value="XI" {{ request('grade') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                <option value="XII" {{ request('grade') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
            </select>
            @if(request('search') || request('grade'))
                <a href="{{ route('admin.school-classes.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tingkat</th>
                        <th>Nama Rombel</th>
                        <th>Slug</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><span class="badge badge-primary">Kelas {{ $item->grade }}</span></td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td><code>{{ $item->slug }}</code></td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.school-classes.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.school-classes.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data kelas ditemukan.</p>
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

@extends('layouts.admin')
@section('page-title', 'Data Guru')
@section('content')
<div class="page-header">
    <div>
        <h1>Data Guru</h1>
        <p>Kelola data guru dan staf pengajar</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.teachers.import') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Guru</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.teachers.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP...">
            </div>
            <select name="status" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="PNS" {{ request('status') == 'PNS' ? 'selected' : '' }}>PNS</option>
                <option value="Honorer" {{ request('status') == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                <option value="PPPK" {{ request('status') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
            </select>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Foto</th>
                        <th>Nama Lengkap</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th>Pendidikan</th>
                        <th>Mengajar Sejak</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                @if($item->photo_path)
                                    <img src="{{ asset('storage/' . $item->photo_path) }}" class="img-avatar" alt="Foto">
                                @else
                                    <div class="sidebar-user-avatar" style="width:36px;height:36px;font-size:14px;">{{ substr($item->name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->nip ?? '—' }}</td>
                            <td>
                                @if($item->status == 'PNS')
                                    <span class="badge badge-success">PNS</span>
                                @elseif($item->status == 'PPPK')
                                    <span class="badge badge-info">PPPK</span>
                                @else
                                    <span class="badge badge-warning">Honorer</span>
                                @endif
                            </td>
                            <td>{{ $item->education ?? '—' }}</td>
                            <td>{{ $item->teaching_since ?? '—' }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.teachers.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.teachers.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data guru ditemukan.</p>
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

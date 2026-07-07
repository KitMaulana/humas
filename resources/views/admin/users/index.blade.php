@extends('layouts.admin')
@section('page-title', 'Kelola Admin')
@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Admin</h1>
        <p>Kelola data pengguna administrator panel</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Admin</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="toolbar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email...">
            </div>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td><code>{{ $item->email }}</code></td>
                            <td>
                                @if($item->id === auth()->id())
                                    <span class="badge badge-success" style="background:#e0f2f1;color:#00796b;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:bold;">Sedang Login</span>
                                @else
                                    <span class="badge" style="background:#f5f5f5;color:#616161;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:bold;">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.users.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    @if($item->id !== auth()->id())
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.users.destroy', $item) }}" method="POST" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-danger" disabled style="opacity:0.5;cursor:not-allowed;" title="Anda tidak dapat menghapus akun Anda sendiri"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada admin ditemukan.</p>
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

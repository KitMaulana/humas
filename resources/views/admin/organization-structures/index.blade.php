@extends('layouts.admin')
@section('page-title', 'Struktur Organisasi')
@section('content')
<div class="page-header">
    <div>
        <h1>Struktur Organisasi</h1>
        <p>Kelola struktur kepengurusan dan jabatan sekolah</p>
    </div>
    <a href="{{ route('admin.organization-structures.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengurus</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Urutan</th>
                        <th style="width: 65px;">Foto</th>
                        <th>Nama Pengurus</th>
                        <th>Jabatan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong>{{ $item->sort_order }}</strong></td>
                            <td>
                                @if($item->photo_path)
                                    <img src="{{ asset('storage/' . $item->photo_path) }}" class="img-avatar" alt="Foto">
                                @else
                                    <div class="sidebar-user-avatar" style="width:36px;height:36px;font-size:14px;">{{ substr($item->name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->position }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.organization-structures.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.organization-structures.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data kepengurusan ditemukan.</p>
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

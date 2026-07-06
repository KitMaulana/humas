@extends('layouts.admin')
@section('page-title', 'Statistik Alumni')
@section('content')
<div class="page-header">
    <div>
        <h1>Statistik Lulusan / Alumni</h1>
        <p>Pantau karir lulusan sekolah pertahun ajaran</p>
    </div>
    <a href="{{ route('admin.alumni-stats.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tahun Angkatan</th>
                        <th>Kuliah / PT</th>
                        <th>Kerja</th>
                        <th>Wirausaha</th>
                        <th>Total Serapan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $total = $item->college_count + $item->work_count + $item->entrepreneur_count;
                        @endphp
                        <tr>
                            <td><strong>Tahun {{ $item->year }}</strong></td>
                            <td><span class="badge badge-info">{{ $item->college_count }} alumni</span></td>
                            <td><span class="badge badge-success">{{ $item->work_count }} alumni</span></td>
                            <td><span class="badge badge-warning">{{ $item->entrepreneur_count }} alumni</span></td>
                            <td><strong>{{ $total }} alumni</strong></td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.alumni-stats.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.alumni-stats.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data statistik alumni ditemukan.</p>
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

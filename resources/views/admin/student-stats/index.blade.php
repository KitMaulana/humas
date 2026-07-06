@extends('layouts.admin')
@section('page-title', 'Statistik Siswa')
@section('content')
<div class="page-header">
    <div>
        <h1>Statistik Jumlah Siswa</h1>
        <p>Kelola jumlah siswa laki-laki dan perempuan per kelas</p>
    </div>
    <a href="{{ route('admin.student-stats.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.student-stats.index') }}" method="GET" class="toolbar">
            <select name="academic_year" class="form-control" style="width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('academic_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            @if(request('academic_year'))
                <a href="{{ route('admin.student-stats.index') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Kelas</th>
                        <th>Siswa Laki-laki</th>
                        <th>Siswa Perempuan</th>
                        <th>Total</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $total = $item->male_count + $item->female_count;
                        @endphp
                        <tr>
                            <td><strong>{{ $item->academic_year }}</strong></td>
                            <td><span class="badge badge-primary">{{ $item->schoolClass->name ?? '—' }}</span></td>
                            <td>{{ $item->male_count }} siswa</td>
                            <td>{{ $item->female_count }} siswi</td>
                            <td><strong>{{ $total }} siswa</strong></td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.student-stats.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.student-stats.destroy', $item) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>Tidak ada data statistik siswa ditemukan.</p>
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

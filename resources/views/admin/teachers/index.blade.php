@extends('layouts.admin')
@section('page-title', 'Data Guru')
@section('content')
<div class="page-header">
    <div>
        <h1>Data Guru</h1>
        <p>Kelola data guru dan staf pengajar</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <button type="submit" form="bulk-delete-form" id="btn-bulk-delete" class="btn btn-danger" style="display: none; align-items: center; gap: 6px;" onclick="return confirm('Apakah Anda yakin ingin menghapus data guru yang terpilih?')">
            <i class="fas fa-trash-alt"></i> Hapus Terpilih (<span id="selected-count">0</span>)
        </button>
        <a href="{{ route('admin.teachers.import') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Guru</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.teachers.index') }}" method="GET" class="toolbar" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
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
            @if(request('search') || request('status') || request('per_page'))
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline">Clear</a>
            @endif

            <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 13px; color: var(--text-secondary); white-space: nowrap;">Tampilkan:</span>
                <select name="per_page" class="form-control" style="width: 100px;" onchange="this.form.submit()">
                    @foreach([15 => '15', 10 => '10', 20 => '20', 50 => '50', 100 => '100', 'semua' => 'Semua'] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('per_page', 15) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <form id="bulk-delete-form" action="{{ route('admin.teachers.bulk-delete') }}" method="POST">
            @csrf
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="select-all-teachers"></th>
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
                                <td style="text-align: center;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="teacher-checkbox"></td>
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
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Tidak ada data guru ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- Hidden delete forms for individual items --}}
        @foreach($items as $item)
            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.teachers.destroy', $item) }}" method="POST" style="display:none;">
                @csrf @method('DELETE')
            </form>
        @endforeach

        <div class="pagination-wrap">
            <div>
                @if(request('per_page') === 'semua' || request('per_page') === 'all')
                    Menampilkan semua {{ $items->total() }} data
                @else
                    Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} data
                @endif
            </div>
            @if(request('per_page') !== 'semua' && request('per_page') !== 'all')
                {{ $items->links() }}
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAllCheckbox = document.getElementById('select-all-teachers');
    const checkboxes = document.querySelectorAll('.teacher-checkbox');
    const btnBulkDelete = document.getElementById('btn-bulk-delete');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.teacher-checkbox:checked').length;
        if (checkedCount > 0) {
            btnBulkDelete.style.display = 'inline-flex';
            selectedCountSpan.textContent = checkedCount;
        } else {
            btnBulkDelete.style.display = 'none';
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', () => {
            checkboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateBulkDeleteButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if (!cb.checked) {
                selectAllCheckbox.checked = false;
            } else {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                selectAllCheckbox.checked = allChecked;
            }
            updateBulkDeleteButton();
        });
    });
});
</script>
@endsection
@endsection

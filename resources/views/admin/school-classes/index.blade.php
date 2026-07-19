@extends('layouts.admin')
@section('page-title', 'Data Kelas')
@section('content')
<div class="page-header">
    <div>
        <h1>Data Kelas</h1>
        <p>Kelola tingkatan kelas dan rombongan belajar</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <button type="submit" form="bulk-delete-form" id="btn-bulk-delete" class="btn btn-danger" style="display: none; align-items: center; gap: 6px;" onclick="return confirm('Apakah Anda yakin ingin menghapus data kelas yang terpilih?')">
            <i class="fas fa-trash-alt"></i> Hapus Terpilih (<span id="selected-count">0</span>)
        </button>
        <a href="{{ route('admin.school-classes.import') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="{{ route('admin.school-classes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kelas</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.school-classes.index') }}" method="GET" class="toolbar" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
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
            @if(request('search') || request('grade') || request('per_page'))
                <a href="{{ route('admin.school-classes.index') }}" class="btn btn-outline">Clear</a>
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

        <form id="bulk-delete-form" action="{{ route('admin.school-classes.bulk-delete') }}" method="POST">
            @csrf
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="select-all-classes"></th>
                            <th>Tingkat</th>
                            <th>Nama Rombel</th>
                            <th>Slug</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td style="text-align: center;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="class-checkbox"></td>
                                <td><span class="badge badge-primary">Kelas {{ $item->grade }}</span></td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td><code>{{ $item->slug }}</code></td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('admin.school-classes.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Tidak ada data kelas ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- Hidden delete forms for individual items --}}
        @foreach($items as $item)
            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.school-classes.destroy', $item) }}" method="POST" style="display:none;">
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
    const selectAllCheckbox = document.getElementById('select-all-classes');
    const checkboxes = document.querySelectorAll('.class-checkbox');
    const btnBulkDelete = document.getElementById('btn-bulk-delete');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.class-checkbox:checked').length;
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

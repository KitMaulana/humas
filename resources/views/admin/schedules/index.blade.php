@extends('layouts.admin')
@section('page-title', 'Jadwal Pelajaran')
@section('content')
<div class="page-header">
    <div>
        <h1>Jadwal Pelajaran</h1>
        <p>Kelola jadwal mata pelajaran mingguan sekolah</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <button type="submit" form="bulk-delete-form" id="btn-bulk-delete" class="btn btn-danger" style="display: none; align-items: center; gap: 6px;" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal yang terpilih?')">
            <i class="fas fa-trash-alt"></i> Hapus Terpilih (<span id="selected-count">0</span>)
        </button>
        <a href="{{ route('admin.schedules.import') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Jadwal</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.schedules.index') }}" method="GET" class="toolbar" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
            <select name="day" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Hari</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                    <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
            <select name="class_id" class="form-control" style="width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            @if(request('day') || request('class_id') || request('per_page'))
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline">Clear</a>
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

        <form id="bulk-delete-form" action="{{ route('admin.schedules.bulk-delete') }}" method="POST">
            @csrf
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="select-all-schedules"></th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td style="text-align: center;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="schedule-checkbox"></td>
                                <td><span class="badge badge-primary">{{ $item->day }}</span></td>
                                <td><strong class="td-time">{{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}</strong></td>
                                <td>
                                    @if($item->title)
                                        <span class="td-mapel" style="font-weight: bold; color: #f59e0b;"><i class="fas fa-bullhorn" style="margin-right: 4px;"></i> {{ $item->title }}</span>
                                    @else
                                        <span class="td-mapel">{{ $item->subject->name ?? '—' }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->teacher->name ?? '—' }}</td>
                                <td>
                                    @if($item->title)
                                        <span class="td-kelas" style="background: rgba(59,130,246,0.15); color: #3b82f6;">Semua Kelas</span>
                                    @else
                                        <span class="td-kelas">{{ $item->schoolClass->name ?? '—' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('admin.schedules.edit', $item) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Tidak ada jadwal pelajaran ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- Hidden delete forms for individual items --}}
        @foreach($items as $item)
            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.schedules.destroy', $item) }}" method="POST" style="display:none;">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAllCheckbox = document.getElementById('select-all-schedules');
    const checkboxes = document.querySelectorAll('.schedule-checkbox');
    const btnBulkDelete = document.getElementById('btn-bulk-delete');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.schedule-checkbox:checked').length;
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

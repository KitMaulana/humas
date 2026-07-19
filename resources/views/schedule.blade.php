@extends('layouts.web')

@section('title', 'Jadwal Pelajaran - SMAN 1 Ciruas')

@section('styles')
<style>
    /* ── JADWAL HERO ───────────────────────────────── */
    .jadwal-hero {
        background: linear-gradient(135deg, var(--primary-blue, #1A3A5C) 0%, #0F2440 60%, #1A4A2E 100%);
        color: white;
        padding: 70px 0 40px;
    }
    .jadwal-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .jadwal-hero p {
        color: rgba(255,255,255,0.7);
        font-size: 1rem;
    }

    /* ── TICKER RUNNING ────────────────────────────── */
    .ticker-wrap {
        background: var(--primary-blue, #1A3A5C);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 24px;
        display: flex;
        align-items: stretch;
        box-shadow: 0 2px 12px rgba(26,58,92,0.15);
        min-height: 48px;
    }
    .ticker-label {
        background: var(--primary-orange, #D4A017);
        color: var(--primary-blue, #1A3A5C);
        padding: 0 18px;
        display: flex;
        align-items: center;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        white-space: nowrap;
        flex-shrink: 0;
        gap: 7px;
    }
    .ticker-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--primary-blue, #1A3A5C);
        animation: pulse-dot 1s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.4); }
    }
    .ticker-track {
        overflow: hidden;
        flex: 1;
        display: flex;
        align-items: center;
        padding: 0 12px;
    }
    .ticker-inner {
        display: flex;
        gap: 0;
        white-space: nowrap;
        animation: ticker-scroll 65s linear infinite;
    }
    .ticker-inner:hover { animation-play-state: paused; }
    .ticker-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 28px 0 0;
        color: white;
        font-size: 13px;
    }
    .ticker-item .t-mapel { font-weight: 700; color: #F0C040; }
    .ticker-item .t-kelas {
        background: rgba(255,255,255,0.15);
        color: white;
        padding: 2px 9px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
    }
    .ticker-item .t-guru { color: rgba(255,255,255,0.65); font-size: 12px; }
    .ticker-item .t-jam { color: rgba(255,255,255,0.5); font-size: 11.5px; }
    .ticker-sep { color: var(--primary-orange, #D4A017); padding: 0 6px; }
    .ticker-empty { color: rgba(255,255,255,0.5); font-size: 13px; font-style: italic; }
    @keyframes ticker-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* ── DAY TABS ──────────────────────────────────── */
    .jadwal-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 20px;
        border-bottom: 2px solid #EDF2F7;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .jadwal-tab {
        padding: 12px 22px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: #4A5568;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        white-space: nowrap;
        transition: color .2s, border-color .2s;
        background: none;
        font-family: inherit;
    }
    .jadwal-tab.active {
        color: var(--primary-blue, #1A3A5C);
        border-bottom-color: var(--primary-orange, #D4A017);
    }
    .jadwal-tab:hover { color: var(--primary-blue, #1A3A5C); }

    /* ── FILTER BAR ────────────────────────────────── */
    .jadwal-filter-bar {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        align-items: flex-end;
        background: #F7FAFF;
        border: 1px solid #E2ECF9;
        border-radius: 10px;
        padding: 16px 18px;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
        min-width: 160px;
    }
    .filter-group label {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary-blue, #1A3A5C);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .filter-group select {
        padding: 9px 36px 9px 12px;
        border: 1.5px solid #CBD5E0;
        border-radius: 7px;
        font-size: 13px;
        color: var(--primary-blue, #1A3A5C);
        background: white;
        cursor: pointer;
        outline: none;
        transition: border-color .2s;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%231A3A5C' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        font-family: inherit;
    }
    .filter-group select:focus { border-color: var(--primary-blue, #1A3A5C); }
    .filter-reset {
        padding: 9px 20px;
        border-radius: 7px;
        background: var(--primary-blue, #1A3A5C);
        color: white;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        align-self: flex-end;
        white-space: nowrap;
        transition: background .2s;
        font-family: inherit;
    }
    .filter-reset:hover { background: #2A5280; }
    .filter-count {
        font-size: 12px;
        color: #4A5568;
        align-self: flex-end;
        padding-bottom: 9px;
        white-space: nowrap;
    }
    .filter-count strong { color: var(--primary-blue, #1A3A5C); }

    /* ── TABLE ──────────────────────────────────────── */
    .jadwal-table-wrap {
        overflow-x: auto;
        border-radius: 10px;
        box-shadow: 0 2px 16px rgba(26,58,92,0.08);
        margin-bottom: 40px;
    }
    .jadwal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .jadwal-table thead th {
        background: var(--primary-blue, #1A3A5C);
        color: white;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .jadwal-table thead th:first-child { border-radius: 10px 0 0 0; }
    .jadwal-table thead th:last-child { border-radius: 0 10px 0 0; }
    .jadwal-table tbody tr {
        border-bottom: 1px solid #EDF2F7;
        transition: background .15s;
    }
    .jadwal-table tbody tr:hover { background: #F7FAFF; }
    .jadwal-table tbody td {
        padding: 13px 16px;
        vertical-align: middle;
    }
    .td-time {
        font-weight: 700;
        color: var(--primary-blue, #1A3A5C);
        white-space: nowrap;
        font-size: 13px;
    }
    .td-mapel { font-weight: 600; color: #2D3748; }
    .td-guru { color: #4A5568; font-size: 12.5px; }
    .td-kelas {
        font-size: 11.5px;
        font-weight: 700;
        background: #EDF2F7;
        color: var(--primary-blue, #1A3A5C);
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-block;
        white-space: nowrap;
    }

    /* Status Badges */
    .badge-live {
        display: inline-flex; align-items: center; gap: 5px;
        background: #FFF5F5; color: #E53E3E;
        padding: 3px 10px 3px 8px; border-radius: 20px;
        font-size: 11px; font-weight: 700;
        border: 1px solid #FEB2B2;
    }
    .badge-live::before {
        content: ''; width: 7px; height: 7px;
        background: #E53E3E; border-radius: 50%;
        animation: pulse-dot 1.2s infinite;
    }
    .badge-done {
        display: inline-flex; align-items: center; gap: 5px;
        background: #F0FFF4; color: #2D7A4F;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        border: 1px solid #9AE6B4;
    }
    .badge-upcoming {
        display: inline-flex; align-items: center; gap: 5px;
        background: #EBF8FF; color: #2B6CB0;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        border: 1px solid #BEE3F8;
    }
    .row-live {
        background: #FFF5F5 !important;
    }
    .row-live:hover { background: #FED7D7 !important; }
    .no-result-row td {
        text-align: center; color: #4A5568;
        padding: 40px; font-style: italic; font-size: 14px;
    }

    /* ── RESPONSIVE ────────────────────────────────── */
    @media (max-width: 768px) {
        .jadwal-filter-bar {
            flex-direction: column;
            gap: 12px;
        }
        .filter-group {
            min-width: 100%;
        }
        .filter-reset {
            align-self: stretch;
            text-align: center;
        }
        .filter-count {
            text-align: center;
            padding-bottom: 0;
        }
        .jadwal-tab {
            padding: 10px 14px;
            font-size: 12px;
        }
        .jadwal-hero h1 {
            font-size: 1.5rem;
        }
        .ticker-label {
            font-size: 9px;
            padding: 0 10px;
        }
    }
    @media (max-width: 480px) {
        .jadwal-filter-bar {
            padding: 12px;
        }
        .jadwal-table {
            font-size: 12px;
        }
        .jadwal-table thead th,
        .jadwal-table tbody td {
            padding: 10px 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="jadwal-hero">
    <div class="container">
        <h1>Jadwal Pelajaran</h1>
        <p>Pantau jadwal pelajaran real-time. Filter berdasarkan kelas, guru, atau mata pelajaran.</p>
    </div>
</div>

<div class="container" style="margin-top: 24px;">
    {{-- TICKER RUNNING TEXT --}}
    <div class="ticker-wrap">
        <div class="ticker-label"><span class="ticker-dot"></span> LIVE SEKARANG</div>
        <div class="ticker-track">
            <div class="ticker-inner" id="tickerInner">
                <span class="ticker-empty">Memuat jadwal berlangsung...</span>
            </div>
        </div>
    </div>

    {{-- DAY TABS --}}
    <div class="jadwal-tabs" id="jadwalTabs">
        @php
            $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        @endphp
        @foreach($daysList as $day)
            <button class="jadwal-tab" data-day="{{ $day }}" onclick="switchDay('{{ $day }}')">{{ $day }}</button>
        @endforeach
    </div>

    {{-- FILTER BAR --}}
    <div class="jadwal-filter-bar">
        <div class="filter-group">
            <label>Filter Kelas</label>
            <select id="filterKelas" onchange="applyFilter()">
                <option value="all">— Semua Kelas —</option>
                @php
                    $currentGrade = '';
                @endphp
                @foreach($classes as $class)
                    @if($class->grade !== $currentGrade)
                        @if($currentGrade !== '')
                            </optgroup>
                        @endif
                        <optgroup label="Kelas {{ $class->grade }}">
                        @php $currentGrade = $class->grade; @endphp
                    @endif
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
                @if($currentGrade !== '')
                    </optgroup>
                @endif
            </select>
        </div>
        <div class="filter-group">
            <label>Filter Guru</label>
            <select id="filterGuru" onchange="applyFilter()">
                <option value="all">— Semua Guru —</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Filter Mata Pelajaran</label>
            <select id="filterMapel" onchange="applyFilter()">
                <option value="all">— Semua Mapel —</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="filter-reset" onclick="resetFilter()">↺ Reset</button>
        <div class="filter-count" id="filterCount"></div>
    </div>

    {{-- TABLE --}}
    <div class="jadwal-table-wrap">
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th>JP</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tbody-main"></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
/* ══════════════════════════════════════════════════
   DATA JADWAL (dari server / Laravel)
══════════════════════════════════════════════════ */
const jadwalData = @json($schedulesJson);
const slotsTemplate = @json($slotsJson);
const recessesTemplate = @json($recessesJson);

/* ══════════════════════════════════════════════════
   STATE
══════════════════════════════════════════════════ */
let currentDay = 'Senin';

/* ══════════════════════════════════════════════════
   TIME & STATUS HELPERS
══════════════════════════════════════════════════ */
function getNow() {
    const now = new Date();
    return now.getHours() * 60 + now.getMinutes();
}

function parseTime(str) {
    const [h, m] = str.split(':').map(Number);
    return h * 60 + m;
}

function getStatus(row) {
    if (!row.start_time || !row.end_time) return 'upcoming';
    const now = getNow();
    const s = parseTime(row.start_time);
    const e = parseTime(row.end_time);
    if (now >= s && now < e) return 'live';
    if (now >= e) return 'done';
    return 'upcoming';
}

function statusBadge(status) {
    if (status === 'live') return '<span class="badge-live">SEDANG BERLANGSUNG</span>';
    if (status === 'done') return '<span class="badge-done">✓ Selesai</span>';
    if (status === 'upcoming') return '<span class="badge-upcoming">⏰ Akan Datang</span>';
    return '<span class="badge-upcoming">—</span>';
}

/* ══════════════════════════════════════════════════
   DETECT TODAY'S DAY
══════════════════════════════════════════════════ */
function autoDay() {
    const dayMap = { 0: null, 1: 'Senin', 2: 'Selasa', 3: 'Rabu', 4: 'Kamis', 5: 'Jumat', 6: 'Sabtu' };
    const d = new Date().getDay();
    if (dayMap[d]) {
        currentDay = dayMap[d];
    }
    updateTabActive();
}

function updateTabActive() {
    document.querySelectorAll('.jadwal-tab').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.day === currentDay);
    });
}

/* ══════════════════════════════════════════════════
   DAY SWITCHING
══════════════════════════════════════════════════ */
function switchDay(day) {
    currentDay = day;
    updateTabActive();
    renderTable();
    buildTicker();
}

/* ══════════════════════════════════════════════════
   FILTER
══════════════════════════════════════════════════ */
function applyFilter() {
    renderTable();
}

function resetFilter() {
    document.getElementById('filterKelas').value = 'all';
    document.getElementById('filterGuru').value = 'all';
    document.getElementById('filterMapel').value = 'all';
    renderTable();
}

function rowMatchesFilter(row) {
    if (row.title) return true; // Global events always match filters

    const fk = document.getElementById('filterKelas').value;
    const fg = document.getElementById('filterGuru').value;
    const fm = document.getElementById('filterMapel').value;

    if (fk !== 'all' && String(row.class_id) !== fk) return false;
    if (fg !== 'all' && String(row.teacher_id) !== fg) return false;
    if (fm !== 'all' && String(row.subject_id) !== fm) return false;
    return true;
}

/* ══════════════════════════════════════════════════
   RENDER TABLE
══════════════════════════════════════════════════ */
function renderTable() {
    const tbody = document.getElementById('tbody-main');
    if (!tbody) return;

    const fk = document.getElementById('filterKelas').value;
    const rows = jadwalData[currentDay] || [];
    tbody.innerHTML = '';
    let count = 0;

    // Detect if current real-world day matches selected tab
    const dayMap = { 0: null, 1: 'Senin', 2: 'Selasa', 3: 'Rabu', 4: 'Kamis', 5: 'Jumat', 6: 'Sabtu' };
    const isToday = dayMap[new Date().getDay()] === currentDay;

    if (fk !== 'all') {
        const classSelect = document.getElementById('filterKelas');
        const className = classSelect.options[classSelect.selectedIndex].text;
        
        // Build timeline
        const daySlots = slotsTemplate[currentDay] || {};
        const dayRecesses = recessesTemplate[currentDay] || [];
        
        const timeline = [];
        
        // Add slots
        Object.keys(daySlots).forEach(num => {
            const slot = daySlots[num];
            timeline.push({
                is_recess: false,
                lesson_number: parseInt(num),
                start_time: slot.start,
                end_time: slot.end
            });
        });
        
        // Add recesses
        dayRecesses.forEach(rec => {
            timeline.push({
                is_recess: true,
                lesson_number: rec.lesson_number,
                start_time: rec.start_time,
                end_time: rec.end_time,
                title: rec.title,
                is_break: rec.is_break
            });
        });
        
        // Sort timeline by start_time
        timeline.sort((a, b) => a.start_time.localeCompare(b.start_time));
        
        timeline.forEach(item => {
            const status = isToday ? getStatus(item) : 'upcoming';
            const trClass = status === 'live' ? 'row-live' : '';
            const jam = item.start_time + '–' + item.end_time;
            
            if (item.is_recess) {
                let icon = 'fa-bullhorn';
                let color = 'var(--primary-orange, #D4A017)';
                let bg = 'rgba(212, 160, 23, 0.06)';
                
                if (item.is_break) {
                    icon = 'fa-coffee';
                    color = '#2ecc71';
                    bg = 'rgba(46, 204, 113, 0.06)';
                }
                
                tbody.innerHTML += `
                    <tr class="${trClass}" style="background: ${bg};">
                        <td style="font-weight: 700; color: ${color};">JP ${item.lesson_number || '—'}</td>
                        <td class="td-time">${jam}</td>
                        <td colspan="3" class="td-mapel" style="font-weight: bold; text-align: left; padding-left: 20px; color: ${color};">
                            <i class="fas ${icon}" style="margin-right: 8px;"></i> ${item.title} (Semua Kelas)
                        </td>
                        <td>${statusBadge(status)}</td>
                    </tr>`;
                count++;
            } else {
                // Find matching schedules in DB rows
                const matches = rows.filter(r => {
                    if (String(r.class_id) !== fk) return false;
                    if (r.lesson_number !== item.lesson_number) return false;
                    
                    // Apply other active filters
                    const fg = document.getElementById('filterGuru').value;
                    const fm = document.getElementById('filterMapel').value;
                    if (fg !== 'all' && String(r.teacher_id) !== fg) return false;
                    if (fm !== 'all' && String(r.subject_id) !== fm) return false;
                    
                    return true;
                });
                
                if (matches.length > 0) {
                    matches.forEach(match => {
                        tbody.innerHTML += `
                            <tr class="${trClass}">
                                <td style="font-weight: 700; color: var(--primary-orange, #D4A017);">JP ${match.lesson_number || '—'}</td>
                                <td class="td-time">${jam}</td>
                                <td class="td-mapel">${match.subject_name}</td>
                                <td class="td-guru">${match.teacher_name}</td>
                                <td><span class="td-kelas">${match.class_name}</span></td>
                                <td>${statusBadge(status)}</td>
                            </tr>`;
                        count++;
                    });
                } else {
                    // Only render empty slots if no other search is active or if it matches the general layout
                    tbody.innerHTML += `
                        <tr class="${trClass}" style="opacity: 0.6;">
                            <td style="font-weight: 700; color: var(--text-muted, #a0aec0);">JP ${item.lesson_number}</td>
                            <td class="td-time">${jam}</td>
                            <td class="td-mapel" style="color: var(--text-muted, #a0aec0); font-style: italic;">—</td>
                            <td class="td-guru" style="color: var(--text-muted, #a0aec0); font-style: italic;">—</td>
                            <td><span class="td-kelas" style="background: #edf2f7; color: #718096;">${className}</span></td>
                            <td>${statusBadge(status)}</td>
                        </tr>`;
                    count++;
                }
            }
        });
    } else {
        // Fallback to original layout
        rows.forEach(row => {
            if (!rowMatchesFilter(row)) return;
            count++;

            const status = isToday ? getStatus(row) : 'upcoming';
            const trClass = status === 'live' ? 'row-live' : '';
            const jam = row.start_time + '–' + row.end_time;

            if (row.title) {
                let icon = 'fa-bullhorn';
                let color = 'var(--primary-orange, #D4A017)';
                let bg = 'rgba(212, 160, 23, 0.06)';
                
                if (row.is_break) {
                    icon = 'fa-coffee';
                    color = '#2ecc71';
                    bg = 'rgba(46, 204, 113, 0.06)';
                }
                
                tbody.innerHTML += `
                    <tr class="${trClass}" style="background: ${bg};">
                        <td style="font-weight: 700; color: ${color};">JP ${row.lesson_number || '—'}</td>
                        <td class="td-time">${jam}</td>
                        <td colspan="3" class="td-mapel" style="font-weight: bold; text-align: left; padding-left: 20px; color: ${color};">
                            <i class="fas ${icon}" style="margin-right: 8px;"></i> ${row.title} (Semua Kelas)
                        </td>
                        <td>${statusBadge(status)}</td>
                    </tr>`;
            } else {
                tbody.innerHTML += `
                    <tr class="${trClass}">
                        <td style="font-weight: 700; color: var(--primary-orange, #D4A017);">JP ${row.lesson_number || '—'}</td>
                        <td class="td-time">${jam}</td>
                        <td class="td-mapel">${row.subject_name}</td>
                        <td class="td-guru">${row.teacher_name}</td>
                        <td><span class="td-kelas">${row.class_name}</span></td>
                        <td>${statusBadge(status)}</td>
                    </tr>`;
            }
        });
    }

    if (count === 0) {
        tbody.innerHTML = '<tr class="no-result-row"><td colspan="6">Tidak ada jadwal yang cocok dengan filter yang dipilih.</td></tr>';
    }

    // Update count label
    const countEl = document.getElementById('filterCount');
    if (countEl) {
        countEl.innerHTML = `Menampilkan <strong>${count}</strong> dari <strong>${rows.length}</strong> sesi`;
    }
}

/* ══════════════════════════════════════════════════
   TICKER — RUNNING TEXT JADWAL BERLANGSUNG
══════════════════════════════════════════════════ */
function buildTicker() {
    const ticker = document.getElementById('tickerInner');
    if (!ticker) return;

    const dayMap = { 0: null, 1: 'Senin', 2: 'Selasa', 3: 'Rabu', 4: 'Kamis', 5: 'Jumat', 6: 'Sabtu' };
    const todayKey = dayMap[new Date().getDay()];
    if (!todayKey) {
        ticker.innerHTML = '<span class="ticker-empty">Hari ini tidak ada jadwal pelajaran (akhir pekan)</span>';
        ticker.style.animation = 'none';
        return;
    }

    const todayRows = jadwalData[todayKey] || [];
    const liveItems = [];
    const upcomingItems = [];

    todayRows.forEach(row => {
        if (!row.start_time || !row.end_time) return;
        const status = getStatus(row);
        if (status === 'live') liveItems.push(row);
        if (status === 'upcoming') {
            const diff = parseTime(row.start_time) - getNow();
            if (diff > 0 && diff <= 60) upcomingItems.push({ ...row, diff });
        }
    });
    upcomingItems.sort((a, b) => a.diff - b.diff);

    if (liveItems.length === 0 && upcomingItems.length === 0) {
        ticker.innerHTML = '<span class="ticker-empty">Tidak ada pelajaran berlangsung saat ini — di luar jam pelajaran</span>';
        ticker.style.animation = 'none';
        return;
    }

    let segments = [];
    liveItems.forEach(r => {
        if (r.title) {
            let icon = 'fa-bullhorn';
            let colorStyle = 'color: #F0C040;';
            if (r.is_break) {
                icon = 'fa-coffee';
                colorStyle = 'color: #2ecc71;';
            }
            segments.push(`<span class="ticker-item">
                <span class="t-mapel" style="${colorStyle}"><i class="fas ${icon}"></i> ${r.title}</span>
                <span class="ticker-sep">•</span>
                <span class="t-kelas">Semua Kelas</span>
                <span class="ticker-sep">•</span>
                <span class="t-jam">${r.start_time}–${r.end_time}</span>
            </span>`);
        } else {
            segments.push(`<span class="ticker-item">
                <span class="t-mapel">${r.subject_name}</span>
                <span class="ticker-sep">•</span>
                <span class="t-kelas">${r.class_name}</span>
                <span class="ticker-sep">•</span>
                <span class="t-guru">${r.teacher_name}</span>
                <span class="ticker-sep">•</span>
                <span class="t-jam">${r.start_time}–${r.end_time}</span>
            </span>`);
        }
    });

    if (upcomingItems.length > 0) {
        const u = upcomingItems[0];
        if (u.title) {
            let icon = 'fa-bullhorn';
            let colorStyle = 'color: #F0C040;';
            if (u.is_break) {
                icon = 'fa-coffee';
                colorStyle = 'color: #2ecc71;';
            }
            segments.push(`<span class="ticker-item" style="opacity:.75">
                ⏭ Berikutnya: <span class="t-mapel" style="${colorStyle}"><i class="fas ${icon}"></i> ${u.title}</span>
                <span class="ticker-sep">•</span><span class="t-kelas">Semua Kelas</span>
                <span class="ticker-sep">•</span><span class="t-jam">${u.start_time}–${u.end_time}</span>
            </span>`);
        } else {
            segments.push(`<span class="ticker-item" style="opacity:.75">
                ⏭ Berikutnya: <span class="t-mapel">${u.subject_name}</span>
                <span class="ticker-sep">•</span><span class="t-kelas">${u.class_name}</span>
                <span class="ticker-sep">•</span><span class="t-guru">${u.teacher_name}</span>
                <span class="ticker-sep">•</span><span class="t-jam">${u.start_time}–${u.end_time}</span>
            </span>`);
        }
    }

    const content = segments.join('<span class="ticker-sep" style="padding:0 20px;color:rgba(255,255,255,0.2)">⬥</span>');
    ticker.innerHTML = content + '<span style="padding:0 40px"></span>' + content;
    
    // Dynamically calculate animation duration for a consistent, readable scroll speed (approx. 45px per second)
    const speed = 45; // Pixels per second
    const contentWidth = ticker.scrollWidth / 2;
    if (contentWidth > 0) {
        const duration = contentWidth / speed;
        ticker.style.animation = `ticker-scroll ${duration}s linear infinite`;
    } else {
        ticker.style.animation = '';
    }
}

/* ══════════════════════════════════════════════════
   INIT
══════════════════════════════════════════════════ */
autoDay();
renderTable();
buildTicker();
setInterval(() => { renderTable(); buildTicker(); }, 60000);
</script>
@endsection

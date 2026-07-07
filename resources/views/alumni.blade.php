@extends('layouts.web')

@section('title', 'Profil Penyerapan Lulusan - SMAN 1 Ciruas')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap');

    .lulusan-section {
        background: linear-gradient(135deg, #1A3A5C 0%, #0F2440 100%);
        color: white;
        padding: 80px 0;
    }
    
    .lulusan-year-tabs {
        display: flex;
        gap: 0;
        justify-content: center;
        margin-bottom: 32px;
        border-bottom: 1px solid rgba(255,255,255,0.15);
        overflow-x: auto;
    }
    .lulusan-year-tab {
        padding: 12px 24px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        border-bottom: 3px solid transparent;
        margin-bottom: -1px;
        background: none;
        border: none;
        transition: all 0.2s ease;
    }
    .lulusan-year-tab.active {
        color: #F0C040;
        border-bottom-color: #D4A017;
    }
    .lulusan-year-tab:hover {
        color: white;
    }

    .lulusan-cards-wrap {
        display: none;
    }
    .lulusan-cards-wrap.active {
        display: block;
    }

    .lulusan-stats {
        display: flex;
        gap: 32px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }
    .lulusan-stat {
        text-align: center;
        min-width: 120px;
    }
    .lulusan-stat strong {
        display: block;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: #F0C040;
    }
    .lulusan-stat small {
        font-size: 11px;
        color: rgba(255,255,255,0.5);
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .lulusan-filter {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }
    .lulusan-filter-btn {
        padding: 10px 24px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid rgba(255,255,255,0.25);
        color: rgba(255,255,255,0.7);
        background: transparent;
        transition: all 0.2s;
    }
    .lulusan-filter-btn.active {
        background: #D4A017;
        color: #1A3A5C;
        border-color: #D4A017;
    }
    .lulusan-filter-btn:hover:not(.active) {
        border-color: #D4A017;
        color: #D4A017;
    }

    .uni-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }
    .uni-card {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background .2s, border-color .2s, transform 0.2s;
        cursor: default;
        text-align: left;
    }
    .uni-card:hover {
        background: rgba(212,160,23,0.08);
        border-color: rgba(212,160,23,0.35);
        transform: translateY(-2px);
    }
    .uni-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: linear-gradient(135deg, #D4A017, #A07010);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .uni-info {
        flex: 1;
        min-width: 0;
    }
    .uni-nama {
        font-weight: 700;
        font-size: 14px;
        color: white;
        line-height: 1.3;
    }
    .uni-count {
        font-size: 12px;
        color: rgba(255,255,255,0.5);
        margin-top: 4px;
    }
    .uni-count span {
        color: #F0C040;
        font-weight: 700;
    }

    .uni-kategori-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #F0C040;
        margin-bottom: 16px;
        margin-top: 36px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        text-align: left;
    }

    .partners-section {
        background-color: var(--white);
        padding: 80px 0;
        border-top: 1.5px solid #edf2f7;
    }
</style>
@endsection

@section('content')
<div class="lulusan-section">
    <div class="container">
        
        <div style="text-align: center; margin-bottom: 56px;">
            <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.25em; color: #D4A017; text-transform: uppercase; display: block; margin-bottom: 12px;">Keberhasilan Alumni</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 700; color: white;">Profil Penyerapan Lulusan</h1>
            <div style="width: 48px; height: 3px; background: #D4A017; margin: 16px auto 0;"></div>
            <p style="margin-top: 16px; color: rgba(255,255,255,0.7); font-size: 15px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.7;">
                Lulusan SMAN 1 Ciruas tersebar di perguruan tinggi terbaik Indonesia. Data terus diperbarui setiap tahun ajaran.
            </p>
        </div>

        @if($alumni->isEmpty())
            @php
                $alumniList = collect([
                    (object)['year' => '2024', 'college_count' => 294, 'work_count' => 12, 'entrepreneur_count' => 6],
                    (object)['year' => '2023', 'college_count' => 278, 'work_count' => 15, 'entrepreneur_count' => 5],
                    (object)['year' => '2022', 'college_count' => 261, 'work_count' => 16, 'entrepreneur_count' => 8],
                ]);
            @endphp
        @else
            @php $alumniList = $alumni; @endphp
        @endif

        <div class="lulusan-year-tabs">
            @foreach($alumniList as $index => $a)
                <button class="lulusan-year-tab {{ $index === 0 ? 'active' : '' }}" onclick="switchYear('{{ $a->year }}')">Angkatan {{ $a->year }}</button>
            @endforeach
        </div>

        @foreach($alumniList as $index => $a)
            @php
                $total = $a->college_count + $a->work_count + $a->entrepreneur_count;
                $rate = $total > 0 ? number_format(($a->college_count / $total) * 100, 1) : 0;
                
                $uniCountMap = [
                    '2024' => 47,
                    '2023' => 43,
                    '2022' => 39,
                ];
                $uniCount = $uniCountMap[$a->year] ?? 35;
            @endphp
            <div class="lulusan-cards-wrap {{ $index === 0 ? 'active' : '' }}" id="lulusan-{{ $a->year }}">
                <div class="lulusan-stats">
                    <div class="lulusan-stat"><strong>{{ $total }}</strong><small>TOTAL LULUSAN</small></div>
                    <div class="lulusan-stat"><strong>{{ $a->college_count }}</strong><small>DITERIMA PT</small></div>
                    <div class="lulusan-stat"><strong>{{ $rate }}%</strong><small>TINGKAT SERAPAN</small></div>
                    <div class="lulusan-stat"><strong>{{ $uniCount }}</strong><small>PERGURUAN TINGGI</small></div>
                </div>
                
                <div class="lulusan-filter" id="lulusan-filter-{{ $a->year }}">
                    <button class="lulusan-filter-btn active" onclick="filterUni('{{ $a->year }}','all')">Semua</button>
                    <button class="lulusan-filter-btn" onclick="filterUni('{{ $a->year }}','ptN')">PTN Favorit</button>
                    <button class="lulusan-filter-btn" onclick="filterUni('{{ $a->year }}','ptS')">PTS</button>
                    <button class="lulusan-filter-btn" onclick="filterUni('{{ $a->year }}','ptn-lokal')">PTN Banten</button>
                </div>
                
                <div id="uni-grid-{{ $a->year }}"></div>
            </div>
        @endforeach

    </div>
</div>

<div class="partners-section">
    <div class="container">
        <div class="section-title" style="text-align: center; margin-bottom: 50px;">
            <h2 style="font-family: 'Playfair Display', serif; color: var(--navy); font-size: 2.2rem;">Mitra Kerja Sama Sekolah</h2>
            <div style="width: 48px; height: 3px; background: var(--primary-orange); margin: 15px auto 0;"></div>
        </div>
        
        @if($partnerships->isEmpty())
            <div style="text-align: center; padding: 40px; color: var(--gray); font-style: italic;">
                Belum ada data kemitraan yang tercatat.
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px;">
                @foreach($partnerships as $partner)
                    <div style="background: white; border: 1px solid #edf2f7; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 15px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.02)';">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: #e0f2f1; color: #00796b; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 15px;">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4 style="color: var(--navy); margin-bottom: 8px; font-weight: 700;">{{ $partner->name }}</h4>
                        <p style="font-size: 0.9rem; color: var(--gray); line-height: 1.5;">{{ $partner->description }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
const uniData = {
  '2024': {
    ptN: [
      { nama: 'Universitas Indonesia', icon: '🏛️', count: 28 },
      { nama: 'IPB University', icon: '🌾', count: 22 },
      { nama: 'Universitas Gadjah Mada', icon: '⚗️', count: 15 },
      { nama: 'ITS Surabaya', icon: '⚙️', count: 12 },
      { nama: 'Universitas Airlangga', icon: '🔬', count: 10 },
      { nama: 'Universitas Diponegoro', icon: '🏗️', count: 9 },
      { nama: 'Universitas Brawijaya', icon: '🌿', count: 8 },
      { nama: 'UIN Jakarta', icon: '☪️', count: 18 },
    ],
    'ptn-lokal': [
      { nama: 'Universitas Sultan Ageng Tirtayasa', icon: '🏫', count: 64 },
      { nama: 'Politeknik Negeri Banten', icon: '⚒️', count: 22 },
      { nama: 'UIN Banten', icon: '📖', count: 19 },
    ],
    ptS: [
      { nama: 'Universitas Bina Nusantara', icon: '💻', count: 14 },
      { nama: 'Universitas Pelita Harapan', icon: '🌟', count: 11 },
      { nama: 'Univ. Mercu Buana', icon: '🏢', count: 9 },
      { nama: 'Univ. Esa Unggul', icon: '📡', count: 7 },
      { nama: 'STIK Pertamina', icon: '🩺', count: 6 },
    ]
  },
  '2023': {
    ptN: [
      { nama: 'Universitas Indonesia', icon: '🏛️', count: 24 },
      { nama: 'IPB University', icon: '🌾', count: 20 },
      { nama: 'Universitas Gadjah Mada', icon: '⚗️', count: 13 },
      { nama: 'ITS Surabaya', icon: '⚙️', count: 10 },
      { nama: 'UIN Jakarta', icon: '☪️', count: 16 },
    ],
    'ptn-lokal': [
      { nama: 'Universitas Sultan Ageng Tirtayasa', icon: '🏫', count: 58 },
      { nama: 'Politeknik Negeri Banten', icon: '⚒️', count: 20 },
      { nama: 'UIN Banten', icon: '📖', count: 17 },
    ],
    ptS: [
      { nama: 'Universitas Bina Nusantara', icon: '💻', count: 12 },
      { nama: 'Universitas Pelita Harapan', icon: '🌟', count: 9 },
      { nama: 'Univ. Mercu Buana', icon: '🏢', count: 8 },
    ]
  },
  '2022': {
    ptN: [
      { nama: 'Universitas Indonesia', icon: '🏛️', count: 20 },
      { nama: 'IPB University', icon: '🌾', count: 18 },
      { nama: 'UIN Jakarta', icon: '☪️', count: 14 },
    ],
    'ptn-lokal': [
      { nama: 'Universitas Sultan Ageng Tirtayasa', icon: '🏫', count: 52 },
      { nama: 'Politeknik Negeri Banten', icon: '⚒️', count: 18 },
    ],
    ptS: [
      { nama: 'Universitas Bina Nusantara', icon: '💻', count: 10 },
      { nama: 'Universitas Pelita Harapan', icon: '🌟', count: 8 },
    ]
  }
};

let activeYear = '{{ $alumniList->first()->year ?? "2024" }}';

function switchYear(year) {
  activeYear = year;
  
  // Update active tab button
  document.querySelectorAll('.lulusan-year-tab').forEach(tab => {
    if (tab.textContent.includes(year)) {
      tab.classList.add('active');
    } else {
      tab.classList.remove('active');
    }
  });

  // Update active cards wrap
  document.querySelectorAll('.lulusan-cards-wrap').forEach(wrap => {
    if (wrap.id === `lulusan-${year}`) {
      wrap.classList.add('active');
    } else {
      wrap.classList.remove('active');
    }
  });

  // Render university cards for this year with default filter 'all'
  filterUni(year, 'all');
}

function filterUni(year, filterType) {
  const container = document.getElementById(`uni-grid-${year}`);
  if (!container) return;

  // Update active filter button
  const filterWrap = document.getElementById(`lulusan-filter-${year}`);
  if (filterWrap) {
    filterWrap.querySelectorAll('.lulusan-filter-btn').forEach(btn => {
      if (btn.getAttribute('onclick').includes(`'${filterType}'`)) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  }

  // Get data
  const data = uniData[year] || uniData['2024'] || { ptN: [], 'ptn-lokal': [], ptS: [] };
  let html = '';

  const renderGroup = (label, list) => {
    if (!list || list.length === 0) return '';
    let groupHtml = `<div class="uni-kategori-label">${label}</div>`;
    groupHtml += `<div class="uni-grid">`;
    list.forEach(uni => {
      groupHtml += `
        <div class="uni-card">
          <div class="uni-icon">${uni.icon}</div>
          <div class="uni-info">
            <div class="uni-nama">${uni.nama}</div>
            <div class="uni-count"><span>${uni.count}</span> mahasiswa</div>
          </div>
        </div>
      `;
    });
    groupHtml += `</div>`;
    return groupHtml;
  };

  if (filterType === 'all') {
    html += renderGroup('Perguruan Tinggi Negeri Favorit', data.ptN);
    html += renderGroup('PTN Wilayah Banten', data['ptn-lokal']);
    html += renderGroup('Perguruan Tinggi Swasta', data.ptS);
  } else if (filterType === 'ptN') {
    html += renderGroup('Perguruan Tinggi Negeri Favorit', data.ptN);
  } else if (filterType === 'ptn-lokal') {
    html += renderGroup('PTN Wilayah Banten', data['ptn-lokal']);
  } else if (filterType === 'ptS') {
    html += renderGroup('Perguruan Tinggi Swasta', data.ptS);
  }

  container.innerHTML = html;
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
  switchYear(activeYear);
});
</script>
@endsection

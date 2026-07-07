<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — SIDACHEERS</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        {{-- SIDEBAR OVERLAY (mobile) --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- SIDEBAR --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('logo.png') }}" class="sidebar-brand-logo" alt="Logo SMAN 1 Ciruas">
                <div class="sidebar-brand-text">
                    <strong>SMAN 1 CIRUAS</strong>
                    <span>ADMIN PANEL</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-group-label">MENU UTAMA</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                <div class="sidebar-group-label">DATA & STATISTIK</div>
                <a href="{{ route('admin.achievements.index') }}" class="sidebar-link {{ request()->routeIs('admin.achievements.*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i> Prestasi
                </a>
                <a href="{{ route('admin.alumni-stats.index') }}" class="sidebar-link {{ request()->routeIs('admin.alumni-stats.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Statistik Alumni
                </a>
                <a href="{{ route('admin.student-stats.index') }}" class="sidebar-link {{ request()->routeIs('admin.student-stats.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Statistik Siswa
                </a>

                <div class="sidebar-group-label">INFORMASI SEKOLAH</div>
                <a href="{{ route('admin.facilities.index') }}" class="sidebar-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Fasilitas
                </a>
                <a href="{{ route('admin.organization-structures.index') }}" class="sidebar-link {{ request()->routeIs('admin.organization-structures.*') ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i> Struktur Organisasi
                </a>
                <a href="{{ route('admin.partnerships.index') }}" class="sidebar-link {{ request()->routeIs('admin.partnerships.*') ? 'active' : '' }}">
                    <i class="fas fa-handshake"></i> Kemitraan
                </a>

                <div class="sidebar-group-label">DATA AKADEMIK</div>
                <a href="{{ route('admin.school-classes.index') }}" class="sidebar-link {{ request()->routeIs('admin.school-classes.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open"></i> Data Kelas
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="sidebar-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Data Guru
                </a>
                <a href="{{ route('admin.staff.index') }}" class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <i class="fas fa-id-badge"></i> Data Staf
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="sidebar-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i> Mata Pelajaran
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="sidebar-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Kelola Admin
                </a>
                <a href="{{ route('admin.lesson-settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.lesson-settings.*') || request()->routeIs('admin.school-profile.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                        <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="main-content">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="topbar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                </div>

                <div class="topbar-center">
                    <div class="supported-by-container">
                        <span class="supp-text">SUPPORTED BY</span>
                        <div class="supp-logos">
                            <span class="logo-ciptakita">CiptaKita.id</span>
                        </div>
                    </div>
                </div>

                <div class="topbar-right">
                    <a href="{{ route('home') }}" class="topbar-web-link" target="_blank" title="Lihat Website">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>

                    <div class="topbar-user">
                        <div class="topbar-user-info">
                            <strong>{{ strtoupper(auth()->user()->name) }}</strong>
                            <span>ADMIN</span>
                        </div>
                        <div class="topbar-user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="topbar-logout-btn" title="Keluar">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="page-content">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success" data-dismiss>
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" data-dismiss>
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
                <button class="btn btn-danger" id="deleteConfirmBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMAN 1 Ciruas - Pusat Informasi')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('styles')
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo-container">
                    <img src="{{ asset('logo.png') }}" alt="Logo SMAN 1 Ciruas" onerror="this.src='https://via.placeholder.com/50x50?text=LOGO'">
                </div>

                <div class="nav-links">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('schedule') }}">Jadwal</a>
                    <a href="{{ route('statistics') }}">Statistik</a>
                    <a href="{{ route('achievements') }}">Prestasi</a>
                    <a href="{{ route('facilities') }}">Fasilitas</a>
                </div>

                <div class="real-time-clock" id="clock">
                    <i class="far fa-clock"></i>
                    <span id="time-display">00:00:00</span>
                </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <h3>SMAN 1 CIRUAS</h3>
                    <p>{{ $profile->address ?? 'Jl. Raya Jakarta-Serang No.Km. 09, Ciruas, Kec. Ciruas, Kabupaten Serang, Banten 42182' }}</p>
                    <p><i class="fas fa-phone"></i> {{ $profile->phone ?? '(0254) 280287' }}</p>
                    <p><i class="fas fa-envelope"></i> {{ $profile->email ?? 'info@sman1ciruas.sch.id' }}</p>
                </div>
                <div class="footer-links">
                    <h4>Tautan Cepat</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('schedule') }}">Jadwal Pelajaran</a></li>
                        <li><a href="{{ route('statistics') }}">Data & Statistik</a></li>
                        <li><a href="{{ route('admin.dashboard') }}">Portal Admin</a></li>
                    </ul>
                </div>
                <div class="footer-social">
                    <h4>Media Sosial</h4>
                    <div style="display: flex; gap: 15px; margin-top: 15px;">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} SMAN 1 Ciruas. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            
            document.getElementById('time-display').innerHTML = `${dateString} | ${timeString.replace(/:/g, '.')}`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @yield('scripts')
</body>
</html>

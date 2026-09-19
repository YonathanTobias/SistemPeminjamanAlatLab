<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pengaturan->nama_sistem ?? 'Sistem Peminjaman Alat Lab' }} - {{ $pengaturan->nama_prodi ?? 'STIKES Panti Waluya' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom STIKES Lab CSS (External & Browser-Cached) -->
    <link rel="stylesheet" href="{{ asset('css/stikes-lab.css') }}">

    <!-- Dynamic Theme Palette per Program Studi -->
    @php
        $theme = $pengaturan->theme_colors ?? [
            'name' => 'blue',
            'primary' => '#0284c7',
            'primary_dark' => '#0369a1',
            'primary_light' => '#e0f2fe',
            'gradient' => 'linear-gradient(135deg, #082f49 0%, #0369a1 40%, #0284c7 80%, #38bdf8 100%)',
            'navbar_bg' => 'rgba(15, 23, 42, 0.96)',
            'navbar_border' => 'rgba(2, 132, 199, 0.2)',
            'badge_soft' => 'rgba(2, 132, 199, 0.15)',
        ];
    @endphp
    <style>
        :root {
            --primary: {{ $theme['primary'] }};
            --primary-dark: {{ $theme['primary_dark'] }};
            --primary-light: {{ $theme['primary_light'] }};
        }
        .navbar-custom {
            background: {{ $theme['navbar_bg'] }} !important;
            border-bottom: 1px solid {{ $theme['navbar_border'] }} !important;
        }
        .footer-custom {
            background: {{ $theme['navbar_bg'] }} !important;
            border-top: 1px solid {{ $theme['navbar_border'] }} !important;
            padding: 2.5rem 0;
            margin-top: auto;
        }
        .hero-banner-prodi {
            background: {{ $theme['gradient'] }} !important;
        }
        .auth-brand-side {
            background: {{ $theme['gradient'] }} !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, {{ $theme['primary'] }} 0%, {{ $theme['primary_dark'] }} 100%) !important;
            border-color: {{ $theme['primary'] }} !important;
        }
        .btn-outline-primary {
            color: {{ $theme['primary'] }} !important;
            border-color: {{ $theme['primary'] }} !important;
        }
        .btn-outline-primary:hover {
            background-color: {{ $theme['primary'] }} !important;
            color: #ffffff !important;
        }
        .badge-soft-primary {
            background-color: {{ $theme['badge_soft'] }} !important;
            color: {{ $theme['primary'] }} !important;
        }
        .text-primary {
            color: {{ $theme['primary'] }} !important;
        }
        .nav-link-custom.active {
            background: {{ $theme['badge_soft'] }} !important;
            border: 1px solid {{ $theme['primary'] }} !important;
            color: #ffffff !important;
        }
        .medical-pass-header {
            background: linear-gradient(135deg, {{ $theme['primary_dark'] }}, {{ $theme['primary'] }}) !important;
        }
        .brand-icon-box {
            background: linear-gradient(135deg, {{ $theme['primary'] }} 0%, {{ $theme['primary_dark'] }} 100%) !important;
        }
        .stepper-progress-line {
            background: linear-gradient(90deg, {{ $theme['primary'] }}, #10b981) !important;
        }
        .stepper-step.active .stepper-circle {
            background-color: {{ $theme['primary'] }} !important;
            border-color: {{ $theme['primary'] }} !important;
            box-shadow: 0 0 16px {{ $theme['badge_soft'] }} !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Modern Sticky Glass Navbar -->
        <nav class="navbar navbar-expand-xl navbar-dark navbar-custom sticky-top">
            <div class="container-fluid px-lg-4">
                <a class="navbar-brand d-flex align-items-center me-3" href="{{ url('/') }}" title="{{ $pengaturan->nama_sistem ?? 'SIM-LAB STIKES' }}">
                    <img src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo" height="38" class="me-2 flex-shrink-0" style="object-fit: contain;">
                    <div>
                        <div class="fw-bold lh-1 text-white" style="font-size: 0.92rem;">SIM-LAB STIKES</div>
                        <small class="text-white-50 d-block" style="font-size: 0.65rem; font-weight: 600; letter-spacing: 0.04em; white-space: nowrap;">{{ strtoupper($pengaturan->nama_prodi ?? 'STIKES PANTI WALUYA') }}</small>
                    </div>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Nav (Public & Admin links) -->
                    <ul class="navbar-nav me-auto mb-2 mb-xl-0 gap-1 align-items-xl-center">
                        <li class="nav-item">
                            <a class="nav-link-custom {{ request()->routeIs('lab.katalog') ? 'active' : '' }}" href="{{ route('lab.katalog') }}">
                                <i class="bi bi-grid"></i> Katalog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-custom {{ request()->routeIs('lab.lacak') ? 'active' : '' }}" href="{{ route('lab.lacak') }}">
                                <i class="bi bi-search-heart"></i> Lacak
                            </a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.peminjaman.*') || request()->routeIs('admin.lab.*') ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">
                                    <i class="bi bi-clipboard-check"></i> Peminjaman
                                </a>
                            </li>

                            <!-- Dropdown Menu Inventaris -->
                            <li class="nav-item dropdown">
                                <a class="nav-link-custom dropdown-toggle {{ request()->routeIs('admin.alat.*') || request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-box-seam"></i> Inventaris
                                </a>
                                <ul class="dropdown-menu shadow-lg border-0 rounded-3 mt-1 p-2">
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('admin.alat.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.alat.index') }}">
                                            <i class="bi bi-boxes"></i> Daftar Peralatan Lab
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('admin.kategori.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.kategori.index') }}">
                                            <i class="bi bi-tags"></i> Kategori Praktikum
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                                </a>
                            </li>

                            <!-- Dropdown Menu Pengaturan & Pengguna -->
                            <li class="nav-item dropdown">
                                <a class="nav-link-custom dropdown-toggle {{ request()->routeIs('admin.pengaturan.*') || request()->routeIs('admin.users.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i> Pengaturan
                                </a>
                                <ul class="dropdown-menu shadow-lg border-0 rounded-3 mt-1 p-2">
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('admin.pengaturan.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.pengaturan.index') }}">
                                            <i class="bi bi-sliders"></i> Profil & Tema Lab
                                        </a>
                                    </li>
                                    @if((Auth::user()->role ?? '') === 'admin_it')
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2 {{ request()->routeIs('admin.users.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.users.index') }}">
                                                <i class="bi bi-people-fill"></i> Kelola Pengguna
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Nav (Theme Toggle, Auth & Profile) -->
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                        <!-- Dark / Light Mode Toggle Button -->
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 d-flex align-items-center gap-2 border-opacity-25" onclick="toggleTheme()" title="Ubah Tema Gelap / Terang">
                                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                                <span class="small d-none d-lg-inline">Mode</span>
                            </button>
                        </li>

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-primary btn-sm px-3 rounded-pill shadow-xs" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right"></i> Login Laboran
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="{{ (Auth::user()->role ?? '') === 'admin_it' ? 'bg-danger' : 'bg-primary' }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 700;">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                        <span class="badge {{ (Auth::user()->role ?? '') === 'admin_it' ? 'bg-danger' : 'bg-primary' }} rounded-pill" style="font-size: 0.65rem; font-weight: 700;">
                                            {{ (Auth::user()->role ?? '') === 'admin_it' ? 'Admin IT' : 'Laboran' }}
                                        </span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" aria-labelledby="navbarDropdown">
                                    <div class="px-3 py-2 border-bottom mb-1">
                                        <div class="small text-muted">Masuk sebagai:</div>
                                        <div class="fw-bold text-dark">{{ Auth::user()->email }}</div>
                                        <div class="mt-1">
                                            <span class="badge {{ (Auth::user()->role ?? '') === 'admin_it' ? 'bg-danger' : 'bg-primary' }}">
                                                {{ (Auth::user()->role ?? '') === 'admin_it' ? 'Hak Akses: Admin IT' : 'Hak Akses: Petugas Laboran' }}
                                            </span>
                                        </div>
                                    </div>
                                    <a class="dropdown-item rounded-3 py-2 text-danger d-flex align-items-center gap-2" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow-1 py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-4 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <div class="flex-grow-1 fw-medium">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-4 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                        <div class="flex-grow-1 fw-medium">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm rounded-4 mb-4" role="alert">
                        <i class="bi bi-info-circle-fill text-info fs-5"></i>
                        <div class="flex-grow-1 fw-medium">{{ session('info') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Modern Footer -->
        <footer class="footer-custom">
            <div class="container">
                <div class="row align-items-center gy-3">
                    <div class="col-md-7 text-center text-md-start d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                        <img src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo" height="48" style="object-fit: contain;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge badge-soft-primary border border-opacity-25 px-2 py-1 rounded-pill small">
                                    <i class="bi bi-hospital"></i> {{ $pengaturan->unit_laboratorium ?? 'Unit Laboratorium' }}
                                </span>
                            </div>
                            <p class="mb-0 text-white-50">{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }} &copy; {{ date('Y') }}. {{ $pengaturan->nama_sistem ?? 'Sistem Informasi Laboratorium' }}.</p>
                        </div>
                    </div>
                    <div class="col-md-5 text-center text-md-end">
                        <span class="text-white-50 small">{{ $pengaturan->alamat_institusi ?? 'Jl. Yulius Usman No.62, Kasin, Klojen, Kota Malang' }} &bull; Telp: {{ $pengaturan->kontak_lab ?? '(0341) 369003' }}</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- HTML5-QRCode Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <!-- Custom App JS -->
    <script src="{{ asset('js/stikes-lab.js') }}"></script>
    @stack('scripts')
</body>
</html>
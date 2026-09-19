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

    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --primary-light: #e0f2fe;
            --secondary: #0f766e;
            --accent: #14b8a6;
            --dark-blue: #0f172a;
            --surface: #ffffff;
            --bg-body: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Modern Glass Navbar */
        .navbar-custom {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.85rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .brand-icon-box {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
            color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        }

        .nav-link-custom {
            color: #94a3b8 !important;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background: rgba(2, 132, 199, 0.25);
            border: 1px solid rgba(2, 132, 199, 0.4);
        }

        /* Card Enhancements */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-header-clean {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.1rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            color: var(--dark-blue);
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.55rem 1.15rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.25);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            transform: translateY(-1px);
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 0.6rem 0.9rem;
            font-size: 0.92rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        /* Soft Badge Pills */
        .badge-soft {
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-soft-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-soft-warning {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-soft-danger {
            background-color: #ffe4e6;
            color: #be123c;
            border: 1px solid #fecdd3;
        }

        .badge-soft-info {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .badge-soft-primary {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-soft-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Modern Tables */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
            padding: 0.9rem 1rem;
            white-space: nowrap;
        }

        .table-custom tbody td {
            padding: 1rem 1rem;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Stat KPI Card */
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.06);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.primary { background: #e0f2fe; color: #0284c7; }
        .stat-icon.success { background: #dcfce7; color: #16a34a; }
        .stat-icon.warning { background: #fef3c7; color: #d97706; }
        .stat-icon.danger { background: #ffe4e6; color: #e11d48; }
        .stat-icon.info { background: #e0e7ff; color: #4f46e5; }

        /* Modern Modals */
        .modal-content {
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.18);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        /* Footer */
        .footer-custom {
            background-color: #0f172a;
            color: #94a3b8;
            padding: 2rem 0;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.85rem;
        }

        .footer-custom a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-custom a:hover {
            color: #38bdf8;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Modern Sticky Glass Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo" height="42" class="me-2" style="object-fit: contain;">
                    <div>
                        <div class="fw-bold lh-1 text-white">{{ $pengaturan->nama_sistem ?? 'SIM-LAB' }}</div>
                        <small class="text-white-50" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.05em;">{{ strtoupper($pengaturan->nama_prodi ?? 'STIKES PANTI WALUYA') }}</small>
                    </div>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Nav (Public links) -->
                    <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link-custom {{ request()->routeIs('lab.katalog') ? 'active' : '' }}" href="{{ route('lab.katalog') }}">
                                <i class="bi bi-grid"></i> Katalog Alat
                            </a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">
                                    <i class="bi bi-clipboard-check"></i> Peminjaman
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.alat.*') ? 'active' : '' }}" href="{{ route('admin.alat.index') }}">
                                    <i class="bi bi-box-seam"></i> Inventaris Alat
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" href="{{ route('admin.pengaturan.index') }}">
                                    <i class="bi bi-gear-fill"></i> Pengaturan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link-custom {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-people-fill"></i> Kelola Pengguna
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Nav (Auth & Profile) -->
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('login') }}">
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
                                <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 px-2 py-1 rounded-pill small">
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
    @stack('scripts')
</body>
</html>
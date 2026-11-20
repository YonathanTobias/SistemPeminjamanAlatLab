@extends('layouts.app')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-10">
        <!-- Header Banner -->
        <div class="text-center mb-4">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="bi bi-radar"></i> Live Tracking & E-Receipt
            </span>
            <h2 class="fw-bold text-dark mb-1">Lacak Status Peminjaman Lab</h2>
            <p class="text-muted small mb-0">Pantau proses verifikasi pengajuan alat praktikum maupun ruangan laboratorium dengan QR Code digital.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-xs mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search Box -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('lab.lacak') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0 text-primary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 border-end-0" placeholder="Ketik Kode Transaksi (contoh: LAB-... atau RNG-...) atau NIM Anda..." value="{{ $search ?? '' }}" required autofocus>
                        <button class="btn btn-primary px-4 fw-bold" type="submit">
                            <i class="bi bi-arrow-right-circle-fill"></i> Lacak Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($search)
            @php
                $totalAlat = $peminjamans->count();
                $totalRuang = $peminjamanRuangans->count();
                $totalSemua = $totalAlat + $totalRuang;

                // Tentukan tab aktif default berdasarkan parameter atau ketersediaan data
                $activeTab = $tab ?? 'semua';
                if ($activeTab === 'ruangan' && $totalRuang === 0 && $totalAlat > 0) {
                    $activeTab = 'alat';
                } elseif ($activeTab === 'alat' && $totalAlat === 0 && $totalRuang > 0) {
                    $activeTab = 'ruangan';
                }
            @endphp

            @if($totalSemua > 0)
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-card-checklist text-primary"></i> Ditemukan {{ $totalSemua }} Riwayat Pengajuan
                        </h5>
                        <small class="text-muted">Kata kunci: <strong>"{{ $search }}"</strong></small>
                    </div>
                    <a href="{{ route('lab.lacak') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Bersihkan Pencarian
                    </a>
                </div>

                <!-- Nav Tabs Filter Jenis Peminjaman -->
                <ul class="nav nav-pills mb-4 gap-2 bg-light p-2 rounded-4 border" id="trackingTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 fw-semibold {{ $activeTab === 'semua' ? 'active' : '' }}" id="semua-tab" data-bs-toggle="pill" data-bs-target="#tab-semua" type="button" role="tab" aria-controls="tab-semua" aria-selected="{{ $activeTab === 'semua' ? 'true' : 'false' }}">
                            <i class="bi bi-grid-fill me-1"></i> Semua Pengajuan ({{ $totalSemua }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 fw-semibold {{ $activeTab === 'alat' ? 'active' : '' }}" id="alat-tab" data-bs-toggle="pill" data-bs-target="#tab-alat" type="button" role="tab" aria-controls="tab-alat" aria-selected="{{ $activeTab === 'alat' ? 'true' : 'false' }}">
                            <i class="bi bi-box-seam me-1"></i> Alat Praktikum ({{ $totalAlat }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 fw-semibold {{ $activeTab === 'ruangan' ? 'active' : '' }}" id="ruangan-tab" data-bs-toggle="pill" data-bs-target="#tab-ruangan" type="button" role="tab" aria-controls="tab-ruangan" aria-selected="{{ $activeTab === 'ruangan' ? 'true' : 'false' }}">
                            <i class="bi bi-door-open-fill me-1"></i> Ruang Laboratorium ({{ $totalRuang }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="trackingTabsContent">
                    <!-- TAB SEMUA -->
                    <div class="tab-pane fade {{ $activeTab === 'semua' ? 'show active' : '' }}" id="tab-semua" role="tabpanel" aria-labelledby="semua-tab">
                        @foreach($peminjamanRuangans as $itemRuang)
                            @include('lab.partials.card-lacak-ruangan', ['item' => $itemRuang])
                        @endforeach

                        @foreach($peminjamans as $itemAlat)
                            @include('lab.partials.card-lacak-alat', ['item' => $itemAlat])
                        @endforeach
                    </div>

                    <!-- TAB ALAT SAJA -->
                    <div class="tab-pane fade {{ $activeTab === 'alat' ? 'show active' : '' }}" id="tab-alat" role="tabpanel" aria-labelledby="alat-tab">
                        @forelse($peminjamans as $itemAlat)
                            @include('lab.partials.card-lacak-alat', ['item' => $itemAlat])
                        @empty
                            <div class="card border-0 rounded-4 shadow-sm p-4 text-center">
                                <p class="text-muted mb-0">Tidak ada pengajuan peminjaman alat untuk pencarian ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- TAB RUANGAN SAJA -->
                    <div class="tab-pane fade {{ $activeTab === 'ruangan' ? 'show active' : '' }}" id="tab-ruangan" role="tabpanel" aria-labelledby="ruangan-tab">
                        @forelse($peminjamanRuangans as $itemRuang)
                            @include('lab.partials.card-lacak-ruangan', ['item' => $itemRuang])
                        @empty
                            <div class="card border-0 rounded-4 shadow-sm p-4 text-center">
                                <p class="text-muted mb-0">Tidak ada pengajuan peminjaman ruangan untuk pencarian ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="card border-0 rounded-4 shadow-sm p-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-search text-muted opacity-50" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Data Peminjaman Tidak Ditemukan</h5>
                    <p class="text-muted small mb-3">Tidak ditemukan transaksi alat maupun ruangan dengan kata kunci: <strong>"{{ $search }}"</strong>. Pastikan Kode Transaksi (LAB-... atau RNG-...) atau NIM sudah sesuai.</p>
                    <div>
                        <a href="{{ route('lab.lacak') }}" class="btn btn-outline-primary btn-sm px-4">
                            <i class="bi bi-arrow-counterclockwise"></i> Coba Lagi
                        </a>
                    </div>
                </div>
            @endif
        @else
            <!-- Petunjuk Penggunaan Awal -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Kirim Pengajuan</h6>
                        <p class="text-muted small mb-0">Pilih peralatan atau ruangan laboratorium, lalu kirimkan formulir booking secara online.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Lacak Kode Transaksi</h6>
                        <p class="text-muted small mb-0">Masukkan Kode TRX (LAB-... atau RNG-...) atau NIM untuk memeriksa status verifikasi dari laboran.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Tunjukkan E-Receipt QR</h6>
                        <p class="text-muted small mb-0">Gunakan QR Code bukti digital pada tiket untuk verifikasi langsung saat serah terima alat atau kunci ruangan.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

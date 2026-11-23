@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden hero-banner-prodi">
    <div class="card-body p-3 p-sm-4 p-lg-5 text-white position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center" style="background: rgba(255, 255, 255, 0.95);">
                        <img src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo" height="42" style="object-fit: contain;">
                    </div>
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill small fw-semibold" style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3); color: #ffffff;">
                            <i class="bi bi-hospital"></i> {{ $pengaturan->nama_prodi ?? 'Prodi S1 Keperawatan' }} &bull; {{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya' }}
                        </div>
                    </div>
                </div>
                <h1 class="h2 fw-bold text-white mb-2">{{ $pengaturan->nama_sistem ?? 'Layanan Peminjaman Laboratorium' }}</h1>
                <p class="mb-4 fs-6 col-lg-10" style="color: rgba(255, 255, 255, 0.9);">
                    Peminjaman paket peralatan medis praktikum dan ruangan laboratorium pada {{ $pengaturan->unit_laboratorium ?? 'Unit Laboratorium' }} secara terpadu.
                </p>

                <!-- Search & Filter Bar inside Hero (Untuk Tab Alat) -->
                <div class="row g-2 col-lg-11" id="heroSearchAlatBar" style="{{ ($activeTab === 'ruangan') ? 'display: none !important;' : '' }}">
                    <div class="col-md-7 position-relative">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 text-muted ps-3">
                                <i class="bi bi-search text-primary"></i>
                            </span>
                            <input type="text" id="searchInputKatalog" class="form-control border-0 py-3 ps-2 shadow-none" placeholder="Cari nama atau kode alat praktikum..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select id="filterKategoriKatalog" class="form-select border-0 py-3 shadow-none fw-semibold">
                            <option value="">Semua Kategori Alat</option>
                            @foreach($kategoriList as $kItem)
                                <option value="{{ $kItem->nama_kategori }}">{{ $kItem->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Info Tombol Booking Cepat (Untuk Tab Ruangan) -->
                <div id="heroActionRuanganBar" style="{{ ($activeTab === 'ruangan') ? '' : 'display: none !important;' }}">
                    <button type="button" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm fw-bold" onclick="bukaModalPinjamRuangan()">
                        <i class="bi bi-calendar-plus text-primary me-1"></i> Form Pengajuan Ruangan
                    </button>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0 d-none d-lg-block">
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25);">
                        <div class="text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.25);">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <div class="small" style="color: rgba(255, 255, 255, 0.88);">Alat Lab Tersedia</div>
                            <div class="fs-5 fw-bold text-white">{{ $stats['total_tersedia'] ?? 0 }} Unit ({{ $stats['total_alat'] ?? 0 }} Jenis)</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25);">
                        <div class="text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.25);">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <div>
                            <div class="small" style="color: rgba(255, 255, 255, 0.88);">Ruangan Lab Siap Pakai</div>
                            <div class="fs-5 fw-bold text-white">{{ $totalRuangTersedia ?? 0 }} dari {{ count($ruangans ?? []) }} Ruang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-xs mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-xs mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Segmented Pill Switcher (Peminjaman Alat vs Peminjaman Ruangan) -->
<div class="d-flex justify-content-center mb-4">
    <div class="p-1 rounded-pill bg-white border shadow-sm d-inline-flex gap-1" style="max-width: 100%; overflow-x: auto;">
        <button type="button" id="btnTabAlat" class="btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition {{ ($activeTab !== 'ruangan') ? 'btn-primary text-white shadow-xs' : 'btn-light text-muted border-0 bg-transparent' }}" onclick="pilihLayanan('alat')">
            <i class="bi bi-boxes"></i>
            <span>Peminjaman Alat Lab ({{ $stats['total_alat'] ?? 0 }} Item)</span>
        </button>
        <button type="button" id="btnTabRuangan" class="btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition {{ ($activeTab === 'ruangan') ? 'btn-primary text-white shadow-xs' : 'btn-light text-muted border-0 bg-transparent' }}" onclick="pilihLayanan('ruangan')">
            <i class="bi bi-door-open-fill"></i>
            <span>Peminjaman Ruangan Lab ({{ count($ruangans ?? []) }} Ruangan)</span>
        </button>
    </div>
</div>

<!-- ================= SECTION 1: LAYANAN ALAT LAB ================= -->
<div id="sectionAlatLab" style="{{ ($activeTab === 'ruangan') ? 'display: none;' : '' }}">
    <!-- Kategori Pill Tabs Bar -->
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 overflow-auto pb-1 category-scroll-container" style="white-space: nowrap;">
            <span class="text-muted small fw-bold me-2 d-none d-md-inline flex-shrink-0"><i class="bi bi-funnel"></i> Kategori:</span>
            @php
                $currentKategori = request('kategori', 'Semua Alat');
            @endphp
            <a href="{{ route('lab.katalog', array_merge(request()->except('kategori', 'page'), ['tab' => 'alat'])) }}" 
               class="btn btn-sm rounded-pill px-3 py-2 fw-semibold flex-shrink-0 {{ $currentKategori === 'Semua Alat' || !request('kategori') ? 'btn-primary shadow-sm' : 'btn-outline-secondary border-opacity-25 bg-white' }}">
                <i class="bi bi-grid-fill me-1"></i> Semua Alat
            </a>
            @foreach($kategoriList as $kItem)
                <a href="{{ route('lab.katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => $kItem->nama_kategori, 'tab' => 'alat'])) }}" 
                   class="btn btn-sm rounded-pill px-3 py-2 fw-semibold flex-shrink-0 {{ $currentKategori === $kItem->nama_kategori ? 'btn-primary shadow-sm' : 'btn-outline-secondary border-opacity-25 bg-white' }}">
                    <i class="bi {{ $kItem->ikon ?: 'bi-tag-fill' }} me-1"></i> {{ $kItem->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Live Filter Empty State Container -->
    <div id="noKatalogResults" class="alert alert-info border-0 shadow-sm rounded-4 p-4 text-center my-3" style="display: none;">
        <i class="bi bi-search fs-2 d-block mb-2 text-primary"></i>
        <h6 class="fw-bold mb-1">Tidak Ada Alat yang Sesuai dengan Pencarian</h6>
        <small class="text-muted">Coba ubah kata kunci atau pilih kategori alat lainnya.</small>
    </div>

    <!-- Grid Katalog Alat -->
    <div class="row g-4 mb-4" id="katalogGrid">
        @forelse($alats as $alat)
            <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch alat-grid-item" 
                 data-nama="{{ $alat->nama_alat }}" 
                 data-kode="{{ $alat->kode_alat }}" 
                 data-kategori="{{ $alat->kategori ?? 'KDM & Tanda Vital' }}">
                <div class="card w-100 border-0 rounded-4 shadow-sm hover-shadow card-hover transition d-flex flex-column overflow-hidden">
                    <!-- Thumbnail Gambar / Icon Placeholder -->
                    <div class="position-relative card-alat-img bg-light border-bottom d-flex align-items-center justify-content-center" style="height: 170px;">
                        @if($alat->gambar && file_exists(public_path($alat->gambar)))
                            <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-100 h-100" style="object-fit: cover;">
                        @else
                            <div class="text-center text-primary opacity-75">
                                @if(str_contains($alat->kode_alat, 'KDM'))
                                    <i class="bi bi-heart-pulse display-4"></i>
                                @elseif(str_contains($alat->kode_alat, 'SIM'))
                                    <i class="bi bi-person-standing display-4"></i>
                                @elseif(str_contains($alat->kode_alat, 'ELK'))
                                    <i class="bi bi-lightning-charge display-4"></i>
                                @elseif(str_contains($alat->kode_alat, 'BED'))
                                    <i class="bi bi-scissors display-4"></i>
                                @else
                                    <i class="bi bi-box-seam display-4"></i>
                                @endif
                            </div>
                        @endif
                        
                        <span class="position-absolute top-0 start-0 m-3 badge badge-soft-primary shadow-xs">
                            <i class="bi bi-tag-fill"></i> {{ $alat->kode_alat }}
                        </span>

                        <!-- Quick Info Button -->
                        <button type="button" class="position-absolute bottom-0 end-0 m-2 btn btn-sm btn-light rounded-circle shadow-sm" 
                                style="width: 32px; height: 32px; padding: 0; background: rgba(255, 255, 255, 0.9); border: 1px solid rgba(0,0,0,0.08);"
                                title="Detail & SOP Alat"
                                onclick='openQuickDetail(@json($alat))'>
                            <i class="bi bi-info-circle text-primary"></i>
                        </button>

                        <span class="position-absolute top-0 end-0 m-3 badge {{ $alat->kondisi == 'Baik' ? 'badge-soft-success' : 'badge-soft-warning' }} shadow-xs">
                            <i class="bi {{ $alat->kondisi == 'Baik' ? 'bi-check2-circle' : 'bi-exclamation-triangle' }}"></i> {{ $alat->kondisi }}
                        </span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column h-100">
                        <!-- Bagian Atas: Kategori & Judul -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-light text-secondary border rounded-pill small" style="font-size: 0.72rem;">
                                    <i class="bi bi-bookmark-fill text-primary"></i> {{ $alat->kategori ?? 'KDM & Tanda Vital' }}
                                </span>
                                <!-- Smart Stock Tier Badge -->
                                @if($alat->stok_tersedia > 5)
                                    <span class="badge badge-soft-success" style="font-size: 0.75rem;">Stok Aman</span>
                                @elseif($alat->stok_tersedia > 0)
                                    <span class="badge badge-soft-warning" style="font-size: 0.75rem;">Stok Menipis</span>
                                @else
                                    <span class="badge badge-soft-danger" style="font-size: 0.75rem;">Stok Habis</span>
                                @endif
                            </div>
                            
                            <h5 class="card-title fw-bold text-dark mb-1 lh-base" style="font-size: 1.05rem; min-height: 2.7rem;" title="{{ $alat->nama_alat }}">
                                {{ $alat->nama_alat }}
                            </h5>
                            <p class="text-muted small mb-0">{{ $pengaturan->unit_laboratorium ?? 'Peralatan Laboratorium Keperawatan' }}</p>
                        </div>

                        <!-- Bagian Stok & Indikator -->
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 px-3 bg-light rounded-3 border">
                                <span class="small text-muted fw-semibold">Tersedia Saat Ini:</span>
                                <div>
                                    <span class="fs-5 fw-bold {{ $alat->stok_tersedia > 0 ? 'text-primary' : 'text-danger' }}">
                                        {{ $alat->stok_tersedia }}
                                    </span>
                                    <span class="text-muted small">/ {{ $alat->stok_total }} Unit</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi Tambah ke Keranjang -->
                            @if($alat->stok_tersedia > 0)
                                <button type="button" class="btn btn-primary w-100 rounded-3 shadow-xs py-2 fw-semibold d-flex align-items-center justify-content-center gap-2"
                                        onclick="addToCart(this)"
                                        data-id="{{ $alat->id }}"
                                        data-kode="{{ $alat->kode_alat }}"
                                        data-nama="{{ $alat->nama_alat }}"
                                        data-stok="{{ $alat->stok_tersedia }}">
                                    <i class="bi bi-cart-plus-fill"></i>
                                    <span>Pilih Alat Praktikum</span>
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary w-100 rounded-3 py-2 fw-semibold opacity-75" disabled>
                                    <i class="bi bi-x-circle me-1"></i> Stok Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 rounded-4 shadow-sm p-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-inbox text-muted opacity-50" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Katalog Alat Praktikum Kosong</h5>
                    <p class="text-muted small mb-0">Belum ada inventaris alat yang terdaftar atau sesuai kriteria filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mb-5">
        {{ $alats->links() }}
    </div>
</div>

<!-- ================= SECTION 2: LAYANAN RUANGAN LAB ================= -->
<div id="sectionRuanganLab" style="{{ ($activeTab === 'ruangan') ? '' : 'display: none;' }}">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-door-open-fill text-primary"></i> Daftar Ruangan & Laboratorium
            </h4>
            <p class="text-muted small mb-0">Pilih salah satu ruangan untuk mengajukan permohonan penggunaan secara online.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" onclick="bukaModalPinjamRuangan()">
            <i class="bi bi-calendar-plus me-1"></i> Form Pengajuan Ruangan
        </button>
    </div>

    <div class="row g-4 mb-5">
        @forelse ($ruangans as $ruang)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill">
                                {{ $ruang->kode_ruangan }}
                            </span>
                            <span class="badge {{ $ruang->status === 'Tersedia' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-1">
                                {{ $ruang->status }}
                            </span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-2">{{ $ruang->nama_ruangan }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1 text-danger"></i> {{ $ruang->lokasi ?? 'Gedung Laboratorium' }}
                        </p>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-people me-1 text-primary"></i> Kapasitas: <strong>{{ $ruang->kapasitas }} Orang</strong>
                        </p>
                        <div class="bg-light p-3 rounded-3 mb-3 flex-grow-1">
                            <small class="text-muted fw-semibold d-block mb-1">Fasilitas:</small>
                            <small class="text-dark">{{ $ruang->fasilitas ?? 'Standar Laboratorium' }}</small>
                        </div>
                        @if ($ruang->status === 'Tersedia')
                            <button type="button" class="btn btn-primary w-100 rounded-pill btn-sm py-2 fw-semibold shadow-sm" onclick="bukaModalPinjamRuangan({{ $ruang->id }}, '{{ addslashes($ruang->nama_ruangan) }} ({{ $ruang->kode_ruangan }}) - Kapasitas {{ $ruang->kapasitas }} Org')">
                                <i class="bi bi-calendar-check me-1"></i> Ajukan Pinjam Ruangan
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary w-100 rounded-pill btn-sm py-2 fw-semibold opacity-75" disabled>
                                <i class="bi bi-lock me-1"></i> Ruangan {{ $ruang->status }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-inbox text-muted display-4 mb-3"></i>
                    <h5 class="fw-bold">Belum Ada Data Ruangan</h5>
                    <p class="text-muted mb-0">Silakan hubungi laboran untuk informasi ketersediaan ruangan.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- FLOATING CIRCULAR CART BUTTON (POJOK KANAN BAWAH - GAYA LAB FARMASI) -->
<button type="button" 
        id="floatingCartBtn" 
        class="floating-cart-btn btn btn-primary shadow-lg d-flex align-items-center justify-content-center" 
        data-bs-toggle="offcanvas" 
        data-bs-target="#offcanvasCart" 
        aria-controls="offcanvasCart" 
        title="Buka Keranjang Peminjaman Alat"
        aria-label="Keranjang Peminjaman Alat"
        style="{{ ($activeTab === 'ruangan') ? 'display: none !important;' : '' }}">
    <div class="position-relative d-flex align-items-center justify-content-center">
        <i class="bi bi-cart3 fs-3 text-white"></i>
        <span id="cartBadgeCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white shadow-sm" style="font-size: 0.72rem; min-width: 22px; height: 22px; padding: 2px 6px;">
            0
        </span>
    </div>
</button>

<!-- OFFCANVAS DRAWER KERANJANG ALAT -->
<div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header border-bottom bg-light">
        <div>
            <h5 class="offcanvas-title fw-bold text-dark" id="offcanvasCartLabel">
                <i class="bi bi-bag-check text-primary"></i> Keranjang Praktikum
            </h5>
            <small class="text-muted">{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-4 d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="small text-muted fw-semibold">Peralatan Dipilih:</span>
            <button type="button" class="btn btn-link text-danger p-0 small text-decoration-none" onclick="clearCart()">
                <i class="bi bi-trash3"></i> Kosongkan
            </button>
        </div>

        <!-- Dynamic Items Container inside Offcanvas -->
        <div id="cartOffcanvasList" class="flex-grow-1 overflow-auto pe-1">
            <!-- Rendered via cart.js -->
        </div>

        <div class="pt-3 border-top mt-auto">
            <button type="button" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm fw-bold d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalKeranjangPinjam" data-bs-dismiss="offcanvas">
                <span>Lanjut Isi Formulir Peminjaman</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- MODAL QUICK DETAIL ALAT & SOP -->
<div class="modal fade" id="modalDetailAlat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="detailAlatNama">-</h5>
                    <span class="badge badge-soft-primary" id="detailAlatKode">-</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <img id="detailAlatFoto" src="" alt="Alat" class="rounded-3 border shadow-xs" style="max-height: 200px; max-width: 100%; object-fit: contain;">
                </div>
                <div class="row g-2 mb-3 small">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <span class="text-muted d-block">Kategori:</span>
                            <strong class="text-dark" id="detailAlatKategori">-</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <span class="text-muted d-block">Status Stok:</span>
                            <strong class="text-success" id="detailAlatStok">-</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <span class="text-muted d-block">Kondisi Fisik:</span>
                            <strong class="text-dark" id="detailAlatKondisi">Baik</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <span class="text-muted d-block">Lokasi Penyimpanan:</span>
                            <strong class="text-dark" id="detailAlatLokasi">Laboratorium</strong>
                        </div>
                    </div>
                </div>
                <div class="p-3 bg-light rounded-3 border">
                    <h6 class="fw-bold text-dark mb-1 small"><i class="bi bi-shield-check text-primary"></i> SOP & Catatan Penggunaan:</h6>
                    <p class="text-muted small mb-0" id="detailAlatDeskripsi">-</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KERANJANG PINJAM (MULTI-ALAT CHECKOUT FORM) -->
<div class="modal fade" id="modalKeranjangPinjam" tabindex="-1" aria-labelledby="modalKeranjangPinjamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('lab.pinjam.store') }}" method="POST" id="formCheckoutPinjam" onsubmit="return validateCartSubmission()">
                @csrf
                
                {{-- Anti-Bot Honeypot (Perangkap Bot Otomatis) --}}
                <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                    <input type="text" name="hp_field" tabindex="-1" autocomplete="off" value="">
                    <input type="hidden" name="form_loaded_at" value="{{ time() }}">
                </div>
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalKeranjangPinjamLabel">
                            <i class="bi bi-cart-check text-primary"></i> Pengajuan Peminjaman Paket Praktikum
                        </h5>
                        <small class="text-muted">{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- Daftar Alat yang Dipilih -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-list-check text-primary"></i> Daftar Alat yang Dipilih (<span id="cartListCount">0</span> Jenis):
                            </h6>
                            <button type="button" class="btn btn-link text-danger text-decoration-none p-0 small" onclick="clearCart()">
                                <i class="bi bi-trash"></i> Kosongkan Keranjang
                            </button>
                        </div>

                        <div class="table-responsive border rounded-3 overflow-hidden">
                            <table class="table table-hover align-middle mb-0" id="cartTable">
                                <thead class="table-light small">
                                    <tr>
                                        <th>Alat Laboratorium</th>
                                        <th class="text-center" style="width: 140px;">Jumlah Pinjam</th>
                                        <th class="text-center" style="width: 60px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    <!-- Items injected dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                        <div id="cartEmptyWarning" class="alert alert-warning small py-2 px-3 mt-2 text-center" style="display: none;">
                            Keranjang praktikum masih kosong. Silakan tambahkan alat dari katalog terlebih dahulu.
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Formulir Identitas Peminjam -->
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-person-lines-fill text-primary"></i> Identitas & Jadwal Praktikum:
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person text-primary"></i> Nama Lengkap Peminjam / Ketua Kelompok
                            </label>
                            <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama Mahasiswa / Dosen" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-card-text text-primary"></i> NIM / NIP
                            </label>
                            <input type="text" name="nim_nip" class="form-control" placeholder="Contoh: 202401001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-mortarboard text-primary"></i> Program Studi / Tingkat
                            </label>
                            <select name="prodi" class="form-select" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <option value="D3 Keperawatan">D3 Keperawatan</option>
                                <option value="S1 Ilmu Keperawatan">S1 Ilmu Keperawatan</option>
                                <option value="Profesi Ners">Profesi Ners</option>
                                <option value="D3 Rekam Medis & Info Kes">D3 Rekam Medis & Info Kes</option>
                                <option value="S1 Farmasi">S1 Farmasi</option>
                                <option value="Dosen / Instruktur Lab">Dosen / Instruktur Lab</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-journal-medical text-primary"></i> Keperluan Praktikum / Mata Kuliah
                            </label>
                            <input type="text" name="keperluan" class="form-control" placeholder="Contoh: Praktikum KMB I, Ujian OSCE, TTV" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar-event text-primary"></i> Tanggal Pinjam
                            </label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar-check text-primary"></i> Rencana Tanggal Kembali
                            </label>
                            <input type="date" name="tgl_kembali_rencana" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left"></i> Tambah Alat Lain
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btnSubmitCheckout">
                        <i class="bi bi-send-fill"></i> Kirim Pengajuan Multi-Alat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL FORM PENGAJUAN PINJAM RUANGAN -->
<div class="modal fade" id="modalPinjamRuangan" tabindex="-1" aria-labelledby="modalPinjamRuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('lab.ruangan.store') }}" method="POST" id="formPengajuanRuangan">
                @csrf
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalPinjamRuanganLabel">
                            <i class="bi bi-calendar-check text-primary me-1"></i> Pengajuan Peminjaman Ruang Laboratorium
                        </h5>
                        <small class="text-muted">{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- Banner Info Ruangan Terpilih -->
                    <div id="selectedRuanganBanner" class="alert alert-info border-0 rounded-3 py-2 px-3 mb-4 d-flex align-items-center gap-2 small">
                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                        <div>
                            <span>Ruangan yang dipilih: <strong id="selectedRuanganNama">-</strong></span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Pilihan Ruangan -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-door-open text-primary"></i> Pilih Laboratorium / Ruangan <span class="text-danger">*</span>
                            </label>
                            <select name="ruangan_id" id="modalSelectRuangan" class="form-select @error('ruangan_id') is-invalid @enderror" required onchange="updateSelectedRuanganBanner()">
                                <option value="">-- Pilih Laboratorium / Ruangan --</option>
                                @foreach ($ruangans as $ruang)
                                    <option value="{{ $ruang->id }}" {{ old('ruangan_id') == $ruang->id ? 'selected' : '' }} {{ $ruang->status !== 'Tersedia' ? 'disabled' : '' }}>
                                        {{ $ruang->nama_ruangan }} ({{ $ruang->kode_ruangan }}) - Kapasitas: {{ $ruang->kapasitas }} Orang {{ $ruang->status !== 'Tersedia' ? ' - '.$ruang->status : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ruangan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Biodata Pemohon -->
                        <div class="col-12 pt-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person-lines-fill text-primary me-1"></i> Biodata Pemohon:
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person text-primary"></i> Nama Lengkap Pemohon <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror" value="{{ old('nama_peminjam') }}" placeholder="Nama Mahasiswa / Dosen" required>
                            @error('nama_peminjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text text-primary"></i> NIM / NIP <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nim_nip" class="form-control @error('nim_nip') is-invalid @enderror" value="{{ old('nim_nip') }}" placeholder="Contoh: 202401001" required>
                            @error('nim_nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-mortarboard text-primary"></i> Program Studi / Tingkat <span class="text-danger">*</span>
                            </label>
                            <select name="prodi" class="form-select @error('prodi') is-invalid @enderror" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <option value="D3 Keperawatan" {{ old('prodi') === 'D3 Keperawatan' ? 'selected' : '' }}>D3 Keperawatan</option>
                                <option value="S1 Ilmu Keperawatan" {{ old('prodi') === 'S1 Ilmu Keperawatan' ? 'selected' : '' }}>S1 Ilmu Keperawatan</option>
                                <option value="Profesi Ners" {{ old('prodi') === 'Profesi Ners' ? 'selected' : '' }}>Profesi Ners</option>
                                <option value="D3 Rekam Medis & Info Kes" {{ old('prodi') === 'D3 Rekam Medis & Info Kes' ? 'selected' : '' }}>D3 Rekam Medis & Info Kes</option>
                                <option value="S1 Farmasi" {{ old('prodi') === 'S1 Farmasi' ? 'selected' : '' }}>S1 Farmasi</option>
                                <option value="Dosen / Instruktur Lab" {{ old('prodi') === 'Dosen / Instruktur Lab' ? 'selected' : '' }}>Dosen / Instruktur Lab</option>
                            </select>
                            @error('prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-medical text-primary"></i> Keperluan Praktikum / Kegiatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" value="{{ old('keperluan') }}" placeholder="Contoh: Praktikum Mandiri KMB, Responsi OSCE" required>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jadwal Peminjaman -->
                        <div class="col-12 pt-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-clock-history text-primary me-1"></i> Jadwal Penggunaan:
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event text-primary"></i> Tanggal Pinjam <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tgl_pinjam" class="form-control @error('tgl_pinjam') is-invalid @enderror" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            @error('tgl_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock text-primary"></i> Jam Mulai <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', '08:00') }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock-fill text-primary"></i> Jam Selesai <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', '10:00') }}" required>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text text-primary"></i> Catatan / Kebutuhan Tambahan (Opsional)
                            </label>
                            <textarea name="catatan" rows="3" class="form-control" placeholder="Tuliskan jika ada kebutuhan peralatan tambahan, perkiraan jumlah peserta, dsb...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                        <i class="bi bi-send-check me-1"></i> Kirim Pengajuan Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalPinjamRuanganInstance = null;

function pilihLayanan(layanan) {
    const secAlat = document.getElementById('sectionAlatLab');
    const secRuangan = document.getElementById('sectionRuanganLab');
    const btnAlat = document.getElementById('btnTabAlat');
    const btnRuangan = document.getElementById('btnTabRuangan');
    const searchAlat = document.getElementById('heroSearchAlatBar');
    const actionRuangan = document.getElementById('heroActionRuanganBar');
    const floatCart = document.getElementById('floatingCartBtn');

    if (layanan === 'ruangan') {
        if (secAlat) secAlat.style.display = 'none';
        if (secRuangan) secRuangan.style.display = 'block';

        if (searchAlat) searchAlat.style.setProperty('display', 'none', 'important');
        if (actionRuangan) actionRuangan.style.setProperty('display', 'block', 'important');
        if (floatCart) floatCart.style.setProperty('display', 'none', 'important');

        if (btnAlat) {
            btnAlat.className = 'btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition btn-light text-muted border-0 bg-transparent';
        }
        if (btnRuangan) {
            btnRuangan.className = 'btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition btn-primary text-white shadow-xs';
        }
    } else {
        if (secAlat) secAlat.style.display = 'block';
        if (secRuangan) secRuangan.style.display = 'none';

        if (searchAlat) searchAlat.style.setProperty('display', 'flex', 'important');
        if (actionRuangan) actionRuangan.style.setProperty('display', 'none', 'important');
        if (floatCart) floatCart.style.removeProperty('display');

        if (btnAlat) {
            btnAlat.className = 'btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition btn-primary text-white shadow-xs';
        }
        if (btnRuangan) {
            btnRuangan.className = 'btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition btn-light text-muted border-0 bg-transparent';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('modalPinjamRuangan');
    if (modalEl && typeof bootstrap !== 'undefined') {
        modalPinjamRuanganInstance = new bootstrap.Modal(modalEl);
    }

    @if ($errors->any())
        pilihLayanan('ruangan');
        if (modalPinjamRuanganInstance) {
            modalPinjamRuanganInstance.show();
        } else if (modalEl && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modalEl).show();
        }
    @endif
});

function bukaModalPinjamRuangan(ruanganId = null, ruanganNama = null) {
    const select = document.getElementById('modalSelectRuangan');
    if (ruanganId && select) {
        select.value = ruanganId;
    }

    updateSelectedRuanganBanner(ruanganNama);

    const modalEl = document.getElementById('modalPinjamRuangan');
    if (modalEl && typeof bootstrap !== 'undefined') {
        if (!modalPinjamRuanganInstance) {
            modalPinjamRuanganInstance = new bootstrap.Modal(modalEl);
        }
        modalPinjamRuanganInstance.show();
    }
}

function updateSelectedRuanganBanner(customText = null) {
    const select = document.getElementById('modalSelectRuangan');
    const banner = document.getElementById('selectedRuanganBanner');
    const label = document.getElementById('selectedRuanganNama');

    if (!banner || !label) return;

    if (customText) {
        label.textContent = customText;
    } else if (select && select.selectedIndex > 0 && select.value) {
        label.textContent = select.options[select.selectedIndex].text;
    } else {
        label.textContent = 'Belum dipilih (silakan pilih ruangan pada menu dropdown di bawah)';
    }
}
</script>
@endsection

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush

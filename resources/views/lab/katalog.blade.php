@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden hero-banner-prodi">
    <div class="card-body p-4 p-lg-5 text-white position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center" style="background: rgba(255, 255, 255, 0.95);">
                        <img src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo" height="42" style="object-fit: contain;">
                    </div>
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill small fw-semibold" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff; backdrop-filter: blur(8px);">
                            <i class="bi bi-hospital"></i> {{ $pengaturan->nama_prodi ?? 'Prodi S1 Keperawatan' }} &bull; {{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya' }}
                        </div>
                    </div>
                </div>
                <h1 class="h2 fw-bold text-white mb-2">{{ $pengaturan->nama_sistem ?? 'Katalog & Peminjaman Alat Praktikum' }}</h1>
                <p class="text-white-50 mb-4 fs-6 col-lg-10">
                    Layanan peminjaman paket alat praktikum pada {{ $pengaturan->unit_laboratorium ?? 'Unit Laboratorium' }} untuk kebutuhan praktikum klinis, skill lab, dan persiapan ujian OSCE.
                </p>

                <!-- Search & Filter Bar inside Hero -->
                <div class="row g-2 col-lg-11">
                    <div class="col-md-7 position-relative">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 text-muted ps-3">
                                <i class="bi bi-search text-primary"></i>
                            </span>
                            <input type="text" id="searchInputKatalog" class="form-control border-0 py-3 ps-2 shadow-none" placeholder="Ketik langsung nama atau kode alat (Live Filter)..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select id="filterKategoriKatalog" class="form-select border-0 py-3 shadow-none fw-semibold">
                            <option value="">-- Semua Kategori Alat --</option>
                            @foreach($kategoriList as $kItem)
                                <option value="{{ $kItem->nama_kategori }}">{{ $kItem->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0 d-none d-lg-block">
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                        <div class="text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small">Total Peralatan Lab</div>
                            <div class="fs-5 fw-bold text-white">{{ $stats['total_alat'] ?? $alats->total() }} Jenis</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                        <div class="text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small">Total Unit Tersedia</div>
                            <div class="fs-5 fw-bold text-white">{{ $stats['total_tersedia'] ?? '-' }} Unit Siap Pakai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Pill Tabs Bar -->
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 overflow-auto pb-2" style="white-space: nowrap;">
        <span class="text-muted small fw-bold me-2 d-none d-md-inline"><i class="bi bi-funnel"></i> Kategori Cepat:</span>
        @php
            $currentKategori = request('kategori', 'Semua Alat');
        @endphp
        <a href="{{ route('lab.katalog', request()->except('kategori', 'page')) }}" 
           class="btn btn-sm rounded-pill px-3 py-2 fw-semibold {{ $currentKategori === 'Semua Alat' || !request('kategori') ? 'btn-primary shadow-sm' : 'btn-outline-secondary border-opacity-25 bg-white' }}">
            <i class="bi bi-grid-fill me-1"></i> Semua Alat
        </a>
        @foreach($kategoriList as $kItem)
            <a href="{{ route('lab.katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => $kItem->nama_kategori])) }}" 
               class="btn btn-sm rounded-pill px-3 py-2 fw-semibold {{ $currentKategori === $kItem->nama_kategori ? 'btn-primary shadow-sm' : 'btn-outline-secondary border-opacity-25 bg-white' }}">
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
                                <span class="badge badge-soft-success" style="font-size: 0.7rem;">Stok Aman</span>
                            @elseif($alat->stok_tersedia > 0)
                                <span class="badge badge-soft-warning" style="font-size: 0.7rem;">Stok Menipis</span>
                            @else
                                <span class="badge badge-soft-danger" style="font-size: 0.7rem;">Stok Habis</span>
                            @endif
                        </div>
                        
                        <h5 class="card-title fw-bold text-dark mb-1 lh-base" style="font-size: 1.05rem; min-height: 2.7rem;" title="{{ $alat->nama_alat }}">
                            {{ $alat->nama_alat }}
                        </h5>
                        <p class="text-muted small mb-0">{{ $pengaturan->unit_laboratorium ?? 'Peralatan Laboratorium Keperawatan' }}</p>
                    </div>
                    
                    <!-- Bagian Bawah: Indikator Stok & Tombol Pinjam -->
                    <div class="mt-auto pt-2">
                        <!-- Stock Status Box -->
                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted fw-medium">Ketersediaan Unit:</span>
                                <span class="fw-bold {{ $alat->stok_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $alat->stok_tersedia }} / {{ $alat->stok_total }} Unit
                                </span>
                            </div>
                            @php
                                $persen = $alat->stok_total > 0 ? ($alat->stok_tersedia / $alat->stok_total) * 100 : 0;
                            @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $alat->stok_tersedia > 5 ? 'bg-success' : ($alat->stok_tersedia > 0 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $persen }}%"></div>
                            </div>
                        </div>

                        @if($alat->stok_tersedia > 0)
                            <button type="button" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold btn-add-cart" 
                                data-id="{{ $alat->id }}"
                                data-kode="{{ $alat->kode_alat }}"
                                data-nama="{{ $alat->nama_alat }}"
                                data-stok="{{ $alat->stok_tersedia }}"
                                onclick="addToCart(this)">
                                <i class="bi bi-cart-plus-fill"></i> + Keranjang Pinjam
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary w-100 rounded-3 py-2 fw-semibold" disabled>
                                <i class="bi bi-slash-circle"></i> Stok Habis Dipinjam
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-search text-muted opacity-50" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Tidak Ada Data Alat Ditemukan</h5>
                <p class="text-muted small mb-3">Silakan coba dengan kategori lain atau bersihkan kata kunci pencarian.</p>
                <div>
                    <a href="{{ route('lab.katalog') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($alats->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $alats->links() }}
    </div>
@endif

<!-- FLOATING CART BUTTON (TRIGGERS OFFCANVAS DRAWER) -->
<div id="floatingCartBtn" class="position-fixed bottom-0 end-0 m-4 z-3" style="display: none;">
    <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-lg py-3 px-4 d-flex align-items-center gap-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" style="border: 2px solid rgba(255,255,255,0.4); font-size: 1rem;">
        <i class="bi bi-bag-check-fill fs-5"></i>
        <span>Keranjang Praktikum</span>
        <span id="cartCountBadge" class="badge bg-white text-dark rounded-pill ms-1 px-2 py-1 fs-6 shadow-xs">0</span>
    </button>
</div>

<!-- MODERN SIDE OFFCANVAS DRAWER (CART PREVIEW & QUICK CHECKOUT) -->
<div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header bg-light border-bottom">
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
@endsection

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush

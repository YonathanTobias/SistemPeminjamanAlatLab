@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0369a1 100%);">
    <div class="card-body p-4 p-lg-5 text-white position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center">
                        <img src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo" height="42" style="object-fit: contain;">
                    </div>
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 text-info small fw-semibold">
                            <i class="bi bi-hospital"></i> {{ $pengaturan->nama_prodi ?? 'Prodi S1 Keperawatan' }} &bull; {{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya' }}
                        </div>
                    </div>
                </div>
                <h1 class="h2 fw-bold text-white mb-2">{{ $pengaturan->nama_sistem ?? 'Katalog & Peminjaman Alat Praktikum' }}</h1>
                <p class="text-white-50 mb-4 fs-6 col-lg-10">
                    Layanan peminjaman paket alat praktikum pada {{ $pengaturan->unit_laboratorium ?? 'Unit Laboratorium' }} untuk kebutuhan praktikum klinis, skill lab, dan persiapan ujian OSCE.
                </p>

                <!-- Search Form inside Hero -->
                <form action="{{ route('lab.katalog') }}" method="GET" class="row g-2 col-lg-11">
                    <div class="col-md-9 position-relative">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 text-muted ps-3">
                                <i class="bi bi-search text-primary"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 py-3 ps-2 shadow-none" placeholder="Cari nama alat medis atau kode (contoh: Stetoskop, KDM-001, Manikin)..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('lab.katalog') }}" class="btn btn-white bg-white text-muted border-0 d-flex align-items-center" title="Reset pencarian">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info w-100 py-3 text-white fw-bold shadow-sm">
                            <i class="bi bi-search"></i> Cari Alat
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0 d-none d-lg-block">
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small">Total Peralatan Lab</div>
                            <div class="fs-5 fw-bold text-white">{{ $stats['total_alat'] ?? $alats->total() }} Jenis</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
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

@if(request('search'))
    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-3 border">
        <div>
            <span class="text-muted small">Hasil pencarian untuk:</span>
            <strong class="text-dark">"{{ request('search') }}"</strong>
            <span class="badge bg-secondary ms-2">{{ $alats->total() }} Ditemukan</span>
        </div>
        <a href="{{ route('lab.katalog') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise"></i> Tampilkan Semua Alat
        </a>
    </div>
@endif

<!-- Grid Katalog Alat -->
<div class="row g-4 mb-4">
    @forelse($alats as $alat)
        <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
            <div class="card w-100 border-0 rounded-4 shadow-sm hover-shadow transition d-flex flex-column">
                <div class="card-body p-4 d-flex flex-column h-100">
                    <!-- Bagian Atas: Badge, Judul, & Kategori -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge badge-soft-primary">
                                <i class="bi bi-tag-fill"></i> {{ $alat->kode_alat }}
                            </span>
                            @if($alat->kondisi == 'Baik')
                                <span class="badge badge-soft-success">
                                    <i class="bi bi-check2-circle"></i> Kondisi: Baik
                                </span>
                            @else
                                <span class="badge badge-soft-warning">
                                    <i class="bi bi-exclamation-triangle"></i> {{ $alat->kondisi }}
                                </span>
                            @endif
                        </div>
                        
                        <h5 class="card-title fw-bold text-dark mb-1 lh-base" style="font-size: 1.05rem; min-height: 2.7rem;" title="{{ $alat->nama_alat }}">{{ $alat->nama_alat }}</h5>
                        <p class="text-muted small mb-0">{{ $pengaturan->unit_laboratorium ?? 'Peralatan Laboratorium Keperawatan' }}</p>
                    </div>
                    
                    <!-- Bagian Bawah: Indikator Stok & Tombol Pinjam (Tersusun Sejajar di Bawah) -->
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
                                <div class="progress-bar {{ $alat->stok_tersedia > 0 ? 'bg-success' : 'bg-danger' }}" role="progressbar" style="width: {{ $persen }}%"></div>
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
                <p class="text-muted small mb-3">Silakan coba dengan kata kunci lain atau bersihkan filter pencarian.</p>
                <div>
                    <a href="{{ route('lab.katalog') }}" class="btn btn-outline-primary btn-sm px-3">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Pencarian
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

<!-- FLOATING CART BUTTON -->
<div id="floatingCartBtn" class="position-fixed bottom-0 end-0 m-4 z-3" style="display: none;">
    <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-lg py-3 px-4 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalKeranjangPinjam" style="border: 2px solid rgba(255,255,255,0.3); font-size: 1rem;">
        <i class="bi bi-bag-check-fill fs-5"></i>
        <span>Keranjang Praktikum</span>
        <span id="cartCountBadge" class="badge bg-danger rounded-pill ms-1 px-2 py-1 fs-6">0</span>
    </button>
</div>

<!-- MODAL KERANJANG PINJAM (MULTI-ALAT CHECKOUT) -->
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
                        <small class="text-muted">Laboratorium Keperawatan STIKES Panti Waluya Malang</small>
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
<script>
    // State Keranjang Praktikum disimpan di localStorage
    const CART_STORAGE_KEY = 'stikes_lab_cart_items';

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem(CART_STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        updateCartUI();
    }

    function addToCart(btn) {
        const id = parseInt(btn.getAttribute('data-id'));
        const kode = btn.getAttribute('data-kode');
        const nama = btn.getAttribute('data-nama');
        const stok = parseInt(btn.getAttribute('data-stok'));

        let cart = getCart();
        const existingIndex = cart.findIndex(item => item.id === id);

        if (existingIndex > -1) {
            if (cart[existingIndex].jumlah < stok) {
                cart[existingIndex].jumlah += 1;
            } else {
                alert(`Maksimal peminjaman untuk ${nama} adalah ${stok} unit (sesuai stok tersedia).`);
                return;
            }
        } else {
            cart.push({
                id: id,
                kode: kode,
                nama: nama,
                stok: stok,
                jumlah: 1
            });
        }

        saveCart(cart);

        // Feedback visual tombol
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Ditambahkan!';
        btn.classList.replace('btn-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.replace('btn-success', 'btn-primary');
        }, 1000);
    }

    function updateQty(id, delta) {
        let cart = getCart();
        const item = cart.find(i => i.id === id);
        if (item) {
            const newQty = item.jumlah + delta;
            if (newQty >= 1 && newQty <= item.stok) {
                item.jumlah = newQty;
                saveCart(cart);
            } else if (newQty > item.stok) {
                alert(`Maksimal ${item.stok} unit.`);
            }
        }
    }

    function setQtyDirect(id, val) {
        let cart = getCart();
        const item = cart.find(i => i.id === id);
        if (item) {
            let num = parseInt(val);
            if (isNaN(num) || num < 1) num = 1;
            if (num > item.stok) num = item.stok;
            item.jumlah = num;
            saveCart(cart);
        }
    }

    function removeFromCart(id) {
        let cart = getCart();
        cart = cart.filter(i => i.id !== id);
        saveCart(cart);
    }

    function clearCart() {
        if (confirm('Yakin ingin mengosongkan keranjang praktikum?')) {
            localStorage.removeItem(CART_STORAGE_KEY);
            updateCartUI();
        }
    }

    function updateCartUI() {
        const cart = getCart();
        const fab = document.getElementById('floatingCartBtn');
        const badge = document.getElementById('cartCountBadge');
        const listCount = document.getElementById('cartListCount');
        const tbody = document.getElementById('cartTableBody');
        const emptyWarning = document.getElementById('cartEmptyWarning');
        const btnSubmit = document.getElementById('btnSubmitCheckout');

        const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);

        if (cart.length > 0) {
            fab.style.display = 'block';
            badge.innerText = `${totalItems} Unit (${cart.length} Alat)`;
            listCount.innerText = cart.length;
            emptyWarning.style.display = 'none';
            btnSubmit.removeAttribute('disabled');
        } else {
            fab.style.display = 'none';
            badge.innerText = '0';
            listCount.innerText = '0';
            emptyWarning.style.display = 'block';
            btnSubmit.setAttribute('disabled', 'disabled');
        }

        // Render Table Rows
        tbody.innerHTML = '';
        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="fw-bold text-dark">${item.nama}</div>
                    <div class="small text-muted d-flex align-items-center gap-2">
                        <span class="badge badge-soft-primary">${item.kode}</span>
                        <span>Tersedia: <strong>${item.stok} Unit</strong></span>
                    </div>
                    <input type="hidden" name="items[${index}][alat_lab_id]" value="${item.id}">
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: auto;">
                        <button type="button" class="btn btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                        <input type="number" name="items[${index}][jumlah]" class="form-control text-center fw-bold px-1" value="${item.jumlah}" min="1" max="${item.stok}" onchange="setQtyDirect(${item.id}, this.value)">
                        <button type="button" class="btn btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-1" style="width: 28px; height: 28px;" onclick="removeFromCart(${item.id})" title="Hapus">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function validateCartSubmission() {
        const cart = getCart();
        if (cart.length === 0) {
            alert('Keranjang praktikum masih kosong! Silakan pilih alat terlebih dahulu.');
            return false;
        }
        // Bersihkan keranjang saat formulir sukses dikirim
        setTimeout(() => {
            localStorage.removeItem(CART_STORAGE_KEY);
            updateCartUI();
        }, 500);
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCartUI();
    });
</script>
@endpush

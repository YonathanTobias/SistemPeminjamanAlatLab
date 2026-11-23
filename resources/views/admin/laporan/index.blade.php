@extends('layouts.app')

@section('content')
<!-- Page Header (Web Only) -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 no-print d-print-none">
    <div>
        <h3 class="fw-bold text-dark mb-1">Pusat Laporan &amp; Kartu Inventaris (KIR)</h3>
        <p class="text-muted small mb-0">Cetak lembar KIR resmi A4, export aset ke Excel, dan rekapitulasi data peminjaman {{ $pengaturan->unit_laboratorium ?? 'Laboratorium' }} {{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya' }}.</p>
    </div>
</div>

<!-- Navigasi Tab Pill Utama Laporan -->
<div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
    <div class="card-body p-2">
        <ul class="nav nav-pills nav-fill gap-2" id="pills-tab-laporan" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2.5 rounded-3 {{ !in_array(request('tab'), ['peminjaman', 'ruangan']) ? 'active' : '' }}" 
                        id="tab-kir-btn" 
                        data-bs-toggle="pill" 
                        data-bs-target="#tab-kir" 
                        type="button" 
                        role="tab" 
                        onclick="setLaporanTab('kir')">
                    <i class="bi bi-card-checklist me-1.5"></i> 1. Kartu Inventaris Alat Lab (KIR)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2.5 rounded-3 {{ request('tab') == 'peminjaman' ? 'active' : '' }}" 
                        id="tab-peminjaman-btn" 
                        data-bs-toggle="pill" 
                        data-bs-target="#tab-peminjaman" 
                        type="button" 
                        role="tab" 
                        onclick="setLaporanTab('peminjaman')">
                    <i class="bi bi-receipt me-1.5"></i> 2. Rekap Peminjaman Alat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2.5 rounded-3 {{ request('tab') == 'ruangan' ? 'active' : '' }}" 
                        id="tab-ruangan-btn" 
                        data-bs-toggle="pill" 
                        data-bs-target="#tab-ruangan" 
                        type="button" 
                        role="tab" 
                        onclick="setLaporanTab('ruangan')">
                    <i class="bi bi-door-open me-1.5"></i> 3. Rekap Booking Ruangan
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- ========================= ISI KONTEN TAB LAPORAN ========================= -->
<div class="tab-content" id="pills-tabContentLaporan">

    <!-- ========================================================================= -->
    <!-- TAB 1: KARTU INVENTARIS ALAT LAB (KIR)                                    -->
    <!-- ========================================================================= -->
    <div class="tab-pane fade {{ !in_array(request('tab'), ['peminjaman', 'ruangan']) ? 'show active' : '' }}" id="tab-kir" role="tabpanel">
        
        <!-- Stat KPI Mini KIR -->
        <div class="row g-3 mb-4 no-print">
            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <div class="stat-icon primary">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Total Item Alat</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($statsKir['total_alat']) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Model / Jenis Alat</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <div class="stat-icon info">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Total Unit Fisik</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($statsKir['total_unit']) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Seluruh Unit Inventaris</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <div class="stat-icon success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Kondisi Baik</span>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($statsKir['kondisi_baik']) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Siap Digunakan</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <div class="stat-icon warning">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Perlu Servis</span>
                        <h4 class="fw-bold mb-0 text-warning">{{ number_format($statsKir['kondisi_rusak']) }}</h4>
                        <span class="text-muted small" style="font-size: 0.75rem;">Rusak / Pemeliharaan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOOLBAR TERPADU (Filter, Search & Action Buttons) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
            <div class="card-body p-3">
                <div class="row g-2.5 align-items-center justify-content-between">
                    <!-- Sisi Kiri: Filter Kategori & Search Bar -->
                    <div class="col-xxl-5 col-xl-5 col-12">
                        <div class="d-flex align-items-center gap-2 flex-wrap flex-sm-nowrap">
                            <!-- Filter Kategori -->
                            <div style="flex: 1 1 auto; min-width: 180px;">
                                <select name="kategori_kir" class="form-select text-truncate" onchange="window.location.href='{{ route('admin.laporan.index') }}?tab=kir&kategori_kir=' + encodeURIComponent(this.value)" title="Pilih Kategori untuk KIR">
                                    <option value="">-- SEMUA KATEGORI (REKAP GLOBAL) --</option>
                                    @foreach($kategoriList as $k)
                                        @php
                                            $katName = is_object($k) ? $k->nama_kategori : $k;
                                        @endphp
                                        <option value="{{ $katName }}" {{ request('kategori_kir') == $katName ? 'selected' : '' }}>
                                            KIR - {{ $katName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Search Bar Realtime -->
                            <div class="input-group" style="width: 200px; flex-shrink: 0;">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" id="tableSearchKIR" class="form-control border-start-0 ps-1" placeholder="Cari nama/kode..." oninput="onKIRTableFilter()">
                                <button class="btn btn-outline-secondary border-start-0 text-muted d-none" type="button" id="btnClearSearchKIR" onclick="clearKIRSearch()" title="Hapus"><i class="bi bi-x"></i></button>
                            </div>

                            <!-- Reset Filter -->
                            <a href="{{ route('admin.laporan.index') }}?tab=kir" class="btn btn-light border text-muted px-2.5 py-2 rounded-3 flex-shrink-0" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Tanggal TTD, Atur TTD, Cetak KIR A4, Export PDF, Export Excel -->
                    <div class="col-xxl-7 col-xl-7 col-12">
                        <div class="d-flex align-items-center justify-content-xl-end gap-2 flex-wrap">
                            <!-- Quick Tanggal TTD -->
                            <div class="d-inline-flex align-items-center gap-1.5 bg-light border rounded-3 px-2.5 py-1.5" title="Tanggal TTD Dokumen KIR">
                                <span class="text-muted small fw-semibold text-nowrap">Tgl TTD:</span>
                                <input type="date" id="quickTanggalKIR" class="form-control form-control-sm border-0 bg-transparent p-0 fw-bold font-monospace" style="width: 120px; font-size: 0.8rem; box-shadow: none;" value="{{ $pengaturan->tanggal_dokumen ? \Carbon\Carbon::parse($pengaturan->tanggal_dokumen)->format('Y-m-d') : date('Y-m-d') }}" onchange="handleQuickDateKIR(this)" aria-label="Tanggal Dokumen">
                            </div>

                            <!-- Atur Penandatangan -->
                            <button type="button" class="btn btn-outline-primary btn-sm fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center text-nowrap shadow-xs" data-bs-toggle="modal" data-bs-target="#modalPengaturanTTD">
                                <i class="bi bi-pen me-1"></i> <span>Atur TTD</span>
                            </button>

                            <!-- Cetak Lembar KIR Langsung A4 -->
                            <button type="button" onclick="triggerPrintTab('kir')" class="btn btn-dark btn-sm fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5 shadow-sm text-nowrap" title="Cetak Dokumen KIR Format HVS A4">
                                <i class="bi bi-printer"></i>
                                <span>Cetak KIR (A4)</span>
                            </button>

                            <!-- Export PDF Resmi -->
                            <a href="{{ route('admin.laporan.kir.pdf', ['kategori' => request('kategori_kir')]) }}" target="_blank" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5 shadow-sm text-nowrap" title="Unduh format PDF dokumen KIR resmi">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                <span>Export PDF</span>
                            </a>

                            <!-- Export Excel Resmi -->
                            <a href="{{ route('admin.laporan.kir.excel', ['kategori' => request('kategori_kir')]) }}" class="btn btn-success btn-sm fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5 shadow-sm text-nowrap" title="Unduh rekap inventaris ke spreadsheet Excel .xlsx">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                                <span>Export Excel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Preview Data KIR -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-data text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">
                        @if(request('kategori_kir'))
                            Data Alat Lab Kategori: <span class="text-primary">{{ request('kategori_kir') }}</span>
                        @else
                            Kartu Inventaris Seluruh Alat Laboratorium (Global)
                        @endif
                    </h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1.5 rounded-pill" id="badgeTotalItemsKIR">{{ $alats->count() }} Total Alat</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0" id="tableKIR">
                    <thead>
                        <tr>
                            <th class="ps-4 text-center" style="width: 50px;">No</th>
                            <th style="width: 130px;">Kode Alat</th>
                            <th style="min-width: 220px;">Nama Alat / Barang Lab</th>
                            <th style="min-width: 160px;">Kategori Praktikum</th>
                            <th class="text-center" style="width: 100px;">Stok Total</th>
                            <th class="text-center" style="width: 100px;">Tersedia</th>
                            <th class="text-center" style="width: 130px;">Kondisi</th>
                            <th class="text-center pe-4" style="width: 140px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyKIR">
                        @forelse($alats as $index => $item)
                        <tr class="kir-row-item"
                            data-no="{{ $index + 1 }}"
                            data-kode="{{ strtolower($item->kode_alat) }}"
                            data-nama="{{ strtolower($item->nama_alat) }}"
                            data-kategori="{{ strtolower($item->kategori ?? '') }}">
                            <td class="ps-4 text-center fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">{{ $item->kode_alat }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_alat }}</div>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary px-2.5 py-1">{{ $item->kategori ?? '-' }}</span>
                            </td>
                            <td class="text-center fw-bold font-monospace text-dark fs-6">{{ $item->stok_total }}</td>
                            <td class="text-center fw-bold font-monospace text-primary fs-6">{{ $item->stok_tersedia }}</td>
                            <td class="text-center">
                                @if($item->kondisi === 'Baik')
                                    <span class="badge-kondisi-status kondisi-baik">
                                        <i class="bi bi-check-circle-fill"></i> Baik
                                    </span>
                                @elseif($item->kondisi === 'Rusak Ringan')
                                    <span class="badge-kondisi-status kondisi-ringan">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Rusak Ringan
                                    </span>
                                @else
                                    <span class="badge-kondisi-status kondisi-berat">
                                        <i class="bi bi-x-circle-fill"></i> Rusak Berat
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4 small text-muted">
                                {{ $item->kondisi === 'Baik' ? 'Siap Pakai' : ($item->kondisi === 'Rusak Ringan' ? 'Perlu Pemeliharaan' : 'Tidak Dapat Dipakai') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 text-muted opacity-50 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark">Tidak Ada Data Alat Lab</h6>
                                <p class="small text-muted mb-0">Belum ada inventaris alat pada kategori yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                        <!-- Baris Kosong Saat Hasil Pencarian Realtime Nihil -->
                        <tr id="kirSearchEmptyRow" style="display: none;">
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-search fs-3 text-muted opacity-50 d-block mb-1"></i>
                                <span>Tidak ditemukan alat yang cocok dengan kata kunci pencarian.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: REKAP PEMINJAMAN ALAT                                              -->
    <!-- ========================================================================= -->
    <div class="tab-pane fade {{ request('tab') == 'peminjaman' ? 'show active' : '' }}" id="tab-peminjaman" role="tabpanel">
        
        <!-- Stat KPI Cards Peminjaman -->
        <div class="row g-3 mb-4 no-print">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card p-3">
                    <div class="stat-icon primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Total Transaksi</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card p-3">
                    <div class="stat-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Menunggu Verifikasi</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['menunggu'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card p-3">
                    <div class="stat-icon info">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Sedang Dipinjam</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['disetujui'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card p-3">
                    <div class="stat-icon success">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Telah Dikembalikan</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['dikembalikan'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card Peminjaman -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 no-print">
            <div class="card-body p-4">
                <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="tab" value="peminjaman">
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">
                            <i class="bi bi-calendar3 text-primary"></i> Periode Mulai Pinjam
                        </label>
                        <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="{{ request('tgl_mulai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">
                            <i class="bi bi-calendar-check text-primary"></i> Periode Selesai Pinjam
                        </label>
                        <input type="date" name="tgl_selesai" class="form-control form-control-sm" value="{{ request('tgl_selesai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">
                            <i class="bi bi-funnel text-primary"></i> Filter Status
                        </label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>🔄 Dikembalikan</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="bi bi-filter"></i> Filter Data
                        </button>
                        @if(request()->hasAny(['tgl_mulai', 'tgl_selesai', 'status']))
                            <a href="{{ route('admin.laporan.index') }}?tab=peminjaman" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Peminjaman Alat -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4 no-print">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Daftar Transaksi Peminjaman Alat Praktikum</h6>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.laporan.csv', request()->query()) }}" class="btn btn-success btn-sm px-3 shadow-xs">
                        <i class="bi bi-file-earmark-excel-fill me-1"></i> Export CSV
                    </a>
                    <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm px-3 shadow-xs">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th style="width: 140px;">Kode Transaksi</th>
                                <th style="min-width: 200px;">Peminjam</th>
                                <th style="min-width: 150px;">Keperluan</th>
                                <th style="min-width: 280px;">Rincian Paket Alat</th>
                                <th class="text-center" style="width: 100px;">Total Unit</th>
                                <th style="width: 150px;">Jadwal Pinjam</th>
                                <th class="text-center pe-4" style="width: 130px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $index => $item)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">{{ $peminjaman->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge badge-soft-primary fw-bold">{{ $item->kode_transaksi ?? ('TRX-' . $item->id) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->nama_peminjam }}</div>
                                        <div class="small text-muted">{{ $item->nim_nip }} &bull; <span class="badge bg-light text-secondary border">{{ $item->prodi }}</span></div>
                                    </td>
                                    <td>
                                        <span class="text-dark small fw-medium">{{ $item->keperluan ?? 'Praktikum' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                            @foreach($item->details as $d)
                                                <span class="badge bg-white text-dark border shadow-xs py-1 px-2">
                                                    <i class="bi bi-box-seam text-primary me-1"></i>
                                                    {{ $d->alat->nama_alat ?? 'Alat Dihapus' }} 
                                                    <strong class="text-primary">({{ $d->jumlah_pinjam }}x)</strong>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-soft-primary fw-bold fs-6 px-3 py-2">
                                            {{ $item->details->sum('jumlah_pinjam') }} Unit
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-muted"><i class="bi bi-calendar-event text-success"></i> {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}</div>
                                            <div class="text-muted"><i class="bi bi-calendar-check text-danger"></i> {{ \Carbon\Carbon::parse($item->tgl_kembali_rencana)->format('d M Y') }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center pe-4">
                                        @if($item->status == 'Disetujui')
                                            <span class="badge-soft badge-soft-primary"><i class="bi bi-check-circle-fill"></i> Dipinjam</span>
                                        @elseif($item->status == 'Ditolak')
                                            <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                                        @elseif($item->status == 'Dikembalikan')
                                            <span class="badge-soft badge-soft-success"><i class="bi bi-check2-all"></i> Dikembalikan</span>
                                        @else
                                            <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-50 d-block mb-2"></i>
                                        <h6 class="fw-bold text-dark">Tidak Ada Data Peminjaman</h6>
                                        <p class="small text-muted mb-0">Sesuaikan filter tanggal atau status di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($peminjaman->hasPages())
                <div class="card-footer bg-white py-3 d-flex justify-content-center border-top">
                    {{ $peminjaman->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: REKAP PEMINJAMAN RUANGAN                                           -->
    <!-- ========================================================================= -->
    <div class="tab-pane fade {{ request('tab') == 'ruangan' ? 'show active' : '' }}" id="tab-ruangan" role="tabpanel">
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4 no-print">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-door-open text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Rekapitulasi Peminjaman / Booking Ruang Laboratorium</h6>
                </div>
                <span class="badge badge-soft-secondary font-monospace">{{ $peminjamanRuangans->total() }} Transaksi Ruangan</span>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="width: 150px;">Kode TRX</th>
                            <th>Ruang Laboratorium</th>
                            <th>Peminjam / Agenda</th>
                            <th style="width: 150px;">Tanggal</th>
                            <th style="width: 150px;">Waktu Jam</th>
                            <th class="text-center pe-4" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanRuangans as $index => $pr)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $peminjamanRuangans->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge badge-soft-primary fw-bold font-monospace">{{ $pr->kode_transaksi }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pr->ruangan->nama_ruangan ?? '-' }}</div>
                                    <small class="text-muted">{{ $pr->ruangan->lokasi ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pr->nama_peminjam }}</div>
                                    <small class="text-muted">{{ $pr->nim_nip }} &bull; {{ $pr->keperluan }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($pr->tgl_pinjam)->format('d M Y') }}</td>
                                <td class="font-monospace small">{{ substr($pr->jam_mulai, 0, 5) }} - {{ substr($pr->jam_selesai, 0, 5) }} WIB</td>
                                <td class="text-center pe-4">
                                    @if($pr->status == 'Disetujui')
                                        <span class="badge-soft badge-soft-primary"><i class="bi bi-check-circle-fill"></i> Disetujui</span>
                                    @elseif($pr->status == 'Selesai')
                                        <span class="badge-soft badge-soft-success"><i class="bi bi-check2-all"></i> Selesai</span>
                                    @elseif($pr->status == 'Ditolak')
                                        <span class="badge-soft badge-soft-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                                    @else
                                        <span class="badge-soft badge-soft-warning"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-door-closed fs-1 text-muted opacity-50 d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark">Belum Ada Data Peminjaman Ruangan</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($peminjamanRuangans->hasPages())
                <div class="card-footer bg-white py-3 d-flex justify-content-center border-top">
                    {{ $peminjamanRuangans->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL ATUR PEJABAT PENANDATANGAN KIR DOKUMEN                             -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalPengaturanTTD" tabindex="-1" aria-labelledby="modalPengaturanTTDLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pen-fill text-warning fs-5"></i>
                    <h5 class="modal-title fw-bold" id="modalPengaturanTTDLabel">Pengaturan Pejabat Penandatangan Dokumen KIR</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPengaturanTTD" action="{{ route('admin.laporan.kir.ttd') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">
                        Data pejabat yang diisi di sini akan dicetak pada lembar <strong>Kartu Inventaris Alat Lab (KIR)</strong> resmi dan disimpan secara permanen di database.
                    </p>

                    <!-- Baris 1: Ketua STIKES -->
                    <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                        <h6 class="fw-bold text-dark mb-2.5">
                            <i class="bi bi-award-fill text-primary me-1.5"></i> 1. Ketua STIKES Panti Waluya Malang (Mengetahui)
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-7">
                                <label class="form-label small text-muted mb-1">Nama Lengkap &amp; Gelar</label>
                                <input type="text" name="nama_ketua" id="inputNamaKetua" class="form-control form-control-sm fw-semibold" value="{{ $pengaturan->nama_ketua ?? 'Wibowo, S.Kep., Ns. M.Biomed' }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">NIP</label>
                                <input type="text" name="nip_ketua" id="inputNipKetua" class="form-control form-control-sm font-monospace" value="{{ $pengaturan->nip_ketua ?? '1283/A' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Kepala Program Studi S1 Keperawatan & Profesi Ners -->
                    <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                        <h6 class="fw-bold text-dark mb-2.5">
                            <i class="bi bi-mortarboard-fill text-success me-1.5"></i> 2. Kepala Program Studi S1 Keperawatan &amp; Profesi Ners
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-7">
                                <label class="form-label small text-muted mb-1">Nama Lengkap &amp; Gelar</label>
                                <input type="text" name="nama_kabag_sarpras" id="inputNamaKabag" class="form-control form-control-sm fw-semibold" value="{{ $pengaturan->nama_kabag_sarpras ?? 'Petrus Tobias, S.Kom.' }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">NIP</label>
                                <input type="text" name="nip_kabag_sarpras" id="inputNipKabag" class="form-control form-control-sm font-monospace" value="{{ $pengaturan->nip_kabag_sarpras ?? 'NIP. 2021.08.045' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Baris 3: Kepala Unit Laboratorium -->
                    <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                        <h6 class="fw-bold text-dark mb-2.5">
                            <i class="bi bi-hospital-fill text-danger me-1.5"></i> 3. Kepala Unit Laboratorium
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-7">
                                <label class="form-label small text-muted mb-1">Nama Lengkap &amp; Gelar</label>
                                <input type="text" name="kepala_lab" id="inputNamaLab" class="form-control form-control-sm fw-semibold" value="{{ $pengaturan->kepala_lab ?? 'Ns. Wening Prabawati, M.Kep.' }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">NIP</label>
                                <input type="text" name="nip_kepala_lab" id="inputNipLab" class="form-control form-control-sm font-monospace" value="{{ $pengaturan->nip_kepala_lab ?? '198205142010122001' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Baris 4: Kota & Tanggal Dokumen -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Kota Dokumen</label>
                            <input type="text" name="kota_dokumen" id="inputKotaDokumen" class="form-control form-control-sm" value="{{ $pengaturan->kota_dokumen ?? 'Malang' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Tanggal Dokumen</label>
                            <input type="date" name="tanggal_dokumen" id="inputTanggalDokumen" class="form-control form-control-sm" value="{{ $pengaturan->tanggal_dokumen ? \Carbon\Carbon::parse($pengaturan->tanggal_dokumen)->format('Y-m-d') : date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- DOKUMEN CETAK KHUSUS PRINT A4 (TERSEMBUNYI SAAT LAYAR WEB)               -->
<!-- ========================================================================= -->
<div id="printDocKIR" class="kir-print-document">
    <!-- KOP SURAT RESMI STIKES PANTI WALUYA MALANG -->
    <div class="d-flex align-items-center justify-content-between pb-1.5 mb-2" style="border-bottom: 2.5px double #000000; font-family: 'Times New Roman', Times, serif;">
        <div style="width: 75px;" class="text-center flex-shrink-0">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="width: 68px; height: auto;" onerror="this.style.display='none'">
        </div>
        <div class="text-center flex-grow-1 px-2">
            <h4 class="fw-bold mb-0 text-uppercase" style="font-size: 1.15rem; letter-spacing: 0.03em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                SEKOLAH TINGGI ILMU KESEHATAN
            </h4>
            <h3 class="fw-bold mb-0.5 text-uppercase" style="font-size: 1.38rem; letter-spacing: 0.04em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                PANTI WALUYA MALANG
            </h3>
            <p class="mb-0 text-dark" style="font-size: 0.78rem; line-height: 1.2; font-family: 'Times New Roman', Times, serif;">
                Jalan Yulius Usman No. 62 Malang &ndash; 65117 Telp (0341) 369003 Fax. 368737
            </p>
            <p class="mb-0 text-dark" style="font-size: 0.78rem; line-height: 1.2; font-family: 'Times New Roman', Times, serif;">
                Email : <u>stikes.pantiwaluyamlg@gmail.com</u>, website : <u>www.stikespantiwaluya.ac.id</u>
            </p>
        </div>
        <div style="width: 75px;" class="flex-shrink-0"></div>
    </div>

    <!-- JUDUL LEMBAR KIR -->
    <div class="text-center mb-2.5">
        <h5 class="fw-bold text-uppercase mb-0.5 text-decoration-underline" style="font-family: 'Times New Roman', Times, serif; font-size: 1.05rem;">
            KARTU INVENTARIS ALAT LABORATORIUM (KIR)
        </h5>
        <div class="fw-semibold small text-uppercase" style="font-family: 'Times New Roman', Times, serif; font-size: 0.82rem;">
            UNIT : {{ strtoupper($pengaturan->unit_laboratorium ?? 'UNIT LABORATORIUM KEPERAWATAN & KESEHATAN') }}
            @if(request('kategori_kir'))
                <br>KATEGORI PRAKTIKUM : {{ strtoupper(request('kategori_kir')) }}
            @else
                <br>REKAPITULASI ASET SELURUH KATEGORI PRAKTIKUM (GLOBAL)
            @endif
        </div>
    </div>

    <!-- TABEL CETAK KIR A4 -->
    <table class="table-kir-print">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">NO</th>
                <th style="width: 95px;" class="text-center">KODE ALAT</th>
                <th>NAMA ALAT / BARANG LABORATORIUM</th>
                <th style="width: 130px;">KATEGORI</th>
                <th style="width: 45px;" class="text-center">JML</th>
                <th style="width: 45px;" class="text-center">READY</th>
                <th style="width: 80px;" class="text-center">KONDISI</th>
                <th style="width: 85px;" class="text-center">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alats as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center font-monospace" style="white-space: nowrap; font-size: 7.5pt;">{{ $item->kode_alat }}</td>
                <td class="fw-bold">{{ $item->nama_alat }}</td>
                <td>{{ $item->kategori ?? '-' }}</td>
                <td class="text-center fw-bold">{{ $item->stok_total }}</td>
                <td class="text-center">{{ $item->stok_tersedia }}</td>
                <td class="text-center" style="white-space: nowrap;">{{ $item->kondisi }}</td>
                <td class="text-center" style="white-space: nowrap; font-size: 7.5pt;">
                    {{ $item->kondisi === 'Baik' ? 'Siap Pakai' : ($item->kondisi === 'Rusak Ringan' ? 'Perlu Pemeliharaan' : 'Perbaikan') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-3">Tidak ada data alat laboratorium yang tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN RESMI 3 PIHAK -->
    <div class="kir-ttd-container mt-4 pt-2" style="font-family: 'Times New Roman', Times, serif;">
        <div class="d-flex justify-content-between align-items-start text-center">
            <!-- 1. Kepala Unit Laboratorium (Kiri) -->
            <div style="width: 290px;">
                <p class="mb-0 invisible" style="font-size: 0.85rem;" aria-hidden="true">&nbsp;</p>
                <p class="fw-bold mb-0" style="font-size: 0.85rem;">Kepala Unit Laboratorium,</p>
                <div style="height: 65px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-lab" style="font-size: 0.85rem; white-space: nowrap;">{{ $pengaturan->kepala_lab ?? 'Ns. Wening Prabawati, M.Kep.' }}</p>
                <p class="mb-0 small font-monospace print-nip-lab" style="font-size: 0.78rem;">NIP. {{ $pengaturan->nip_kepala_lab ?? '198205142010122001' }}</p>
            </div>

            <!-- 2. Kepala Program Studi (Kanan) -->
            <div style="width: 290px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.85rem;">{{ $pengaturan->kota_dokumen ?? 'Malang' }}, {{ \Carbon\Carbon::parse($pengaturan->tanggal_dokumen ?? date('Y-m-d'))->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.85rem;">Kepala Program Studi S1 Keperawatan &amp; Profesi Ners,</p>
                <div style="height: 65px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.85rem; white-space: nowrap;">{{ $pengaturan->nama_kabag_sarpras ?? 'Petrus Tobias, S.Kom.' }}</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.78rem;">{{ $pengaturan->nip_kabag_sarpras ?? 'NIP. 2021.08.045' }}</p>
            </div>
        </div>

        <!-- Baris 2: Ketua STIKES (Tengah Bawah) -->
        <div class="d-flex justify-content-center text-center mt-4">
            <div style="width: 340px;">
                <p class="mb-0" style="font-size: 0.85rem;">Mengetahui,</p>
                <p class="fw-bold mb-0" style="font-size: 0.85rem;">Ketua STIKES Panti Waluya Malang</p>
                <div style="height: 65px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.85rem; white-space: nowrap;">{{ $pengaturan->nama_ketua ?? 'Wibowo, S.Kep., Ns. M.Biomed' }}</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.78rem;">NIP. {{ $pengaturan->nip_ketua ?? '1283/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/laporan.js') }}"></script>
@endpush

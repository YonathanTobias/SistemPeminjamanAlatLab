@extends('layouts.app')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-10">
        <!-- Header Banner -->
        <div class="text-center mb-4">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="bi bi-radar"></i> Live Tracking & E-Receipt
            </span>
            <h2 class="fw-bold text-dark mb-1">Lacak Status Peminjaman Alat Lab</h2>
            <p class="text-muted small mb-0">Pantau proses verifikasi pengajuan alat dan dapatkan bukti digital (QR Code) untuk pengambilan di laboratorium.</p>
        </div>

        <!-- Search Box -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('lab.lacak') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0 text-primary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 border-end-0" placeholder="Ketik Kode Transaksi (contoh: {{ $pengaturan->prodi_prefix ?? 'LAB' }}-{{ date('Ymd') }}-001) atau NIM Anda..." value="{{ $search ?? '' }}" required autofocus>
                        <button class="btn btn-primary px-4 fw-bold" type="submit">
                            <i class="bi bi-arrow-right-circle-fill"></i> Lacak Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($search)
            @if($peminjamans->isNotEmpty())
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-card-checklist text-primary"></i> Ditemukan {{ $peminjamans->count() }} Riwayat Pengajuan
                    </h5>
                    <a href="{{ route('lab.lacak') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Bersihkan Pencarian
                    </a>
                </div>

                @foreach($peminjamans as $item)
                    <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden">
                        <!-- Card Header Status -->
                        <div class="p-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom hero-banner-prodi text-white">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-white text-dark fw-bold px-3 py-2 fs-6">
                                    {{ $item->kode_transaksi ?? ('TRX-' . $item->id) }}
                                </span>
                                <span class="text-white-50 small">Diajukan: {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }} WIB</span>
                            </div>
                            <div>
                                @if($item->status == 'Disetujui')
                                    <span class="badge bg-success px-3 py-2 fs-6">
                                        <i class="bi bi-check-circle-fill"></i> DISETUJUI / SIAP DIAMBIL
                                    </span>
                                @elseif($item->status == 'Dikembalikan')
                                    <span class="badge bg-info text-dark px-3 py-2 fs-6">
                                        <i class="bi bi-check2-all"></i> TELAH DIKEMBALIKAN
                                    </span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="badge bg-danger px-3 py-2 fs-6">
                                        <i class="bi bi-x-circle-fill"></i> DITOLAK
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                        <i class="bi bi-hourglass-split"></i> MENUNGGU VERIFIKASI
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Visual Stepper Progress Tracker -->
                            <div class="mb-4 p-4 bg-light rounded-4 border">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bezier2 text-primary"></i> Progres Status Peminjaman:</h6>
                                
                                @php
                                    $status = $item->status;
                                    $step1Class = 'completed';
                                    $step2Class = ($status == 'Ditolak') ? 'rejected' : (($status == 'Menunggu') ? 'active' : 'completed');
                                    $step3Class = ($status == 'Disetujui') ? 'active' : (($status == 'Dikembalikan') ? 'completed' : '');
                                    $step4Class = ($status == 'Dikembalikan') ? 'completed' : '';
                                    
                                    $lineWidth = '0%';
                                    if ($status == 'Menunggu') $lineWidth = '25%';
                                    elseif ($status == 'Disetujui') $lineWidth = '65%';
                                    elseif ($status == 'Dikembalikan') $lineWidth = '100%';
                                    elseif ($status == 'Ditolak') $lineWidth = '33%';
                                @endphp

                                <div class="stepper-wrapper">
                                    <div class="stepper-progress-line" style="width: {{ $lineWidth }}; {{ $status == 'Ditolak' ? 'background: #ef4444;' : '' }}"></div>
                                    
                                    <!-- Step 1 -->
                                    <div class="stepper-step {{ $step1Class }}">
                                        <div class="stepper-circle">
                                            <i class="bi bi-send-check"></i>
                                        </div>
                                        <div class="stepper-title">1. Diajukan</div>
                                        <div class="stepper-sub">Form Terkirim</div>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="stepper-step {{ $step2Class }}">
                                        <div class="stepper-circle">
                                            <i class="bi {{ $status == 'Ditolak' ? 'bi-x-lg' : 'bi-shield-check' }}"></i>
                                        </div>
                                        <div class="stepper-title">{{ $status == 'Ditolak' ? '2. Ditolak' : '2. Verifikasi' }}</div>
                                        <div class="stepper-sub">{{ $status == 'Menunggu' ? 'Sedang Diperiksa' : ($status == 'Ditolak' ? 'Permohonan Ditolak' : 'Disetujui Laboran') }}</div>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="stepper-step {{ $step3Class }}">
                                        <div class="stepper-circle">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div class="stepper-title">3. Dipakai</div>
                                        <div class="stepper-sub">{{ $status == 'Disetujui' ? 'Siap Diambil' : ($status == 'Dikembalikan' ? 'Selesai Dipakai' : 'Menunggu') }}</div>
                                    </div>

                                    <!-- Step 4 -->
                                    <div class="stepper-step {{ $step4Class }}">
                                        <div class="stepper-circle">
                                            <i class="bi bi-check2-all"></i>
                                        </div>
                                        <div class="stepper-title">4. Selesai</div>
                                        <div class="stepper-sub">{{ $status == 'Dikembalikan' ? 'Alat Kembali' : 'Belum Kembali' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 align-items-center">
                                <!-- Data Identitas & Paket Alat -->
                                <div class="col-md-7">
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">Nama Peminjam:</span>
                                            <strong class="text-dark">{{ $item->nama_peminjam }}</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">NIM / NIP & Prodi:</span>
                                            <strong class="text-dark">{{ $item->nim_nip }} ({{ $item->prodi }})</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">Jadwal Pinjam:</span>
                                            <span class="text-dark fw-medium"><i class="bi bi-calendar-event text-success"></i> {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($item->tgl_kembali_rencana)->format('d M Y') }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">Keperluan:</span>
                                            <span class="text-dark fw-medium">{{ $item->keperluan ?? 'Praktikum Laboratorium' }}</span>
                                        </div>
                                    </div>

                                    @if($item->catatan)
                                        <div class="alert alert-warning py-2 px-3 small rounded-3 mb-3">
                                            <i class="bi bi-info-circle-fill me-1"></i> <strong>Catatan Laboran:</strong> {{ $item->catatan }}
                                        </div>
                                    @endif

                                    <!-- List Paket Alat -->
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-boxes text-primary"></i> Daftar Paket Peralatan Medis ({{ $item->details->sum('jumlah_pinjam') }} Unit):</h6>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($item->details as $d)
                                            <div class="p-2 px-3 bg-white border rounded-3 shadow-xs d-flex align-items-center gap-2">
                                                <i class="bi bi-box-seam text-primary"></i>
                                                <div>
                                                    <span class="fw-semibold text-dark small d-block">{{ $d->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                                    <small class="text-muted">{{ $d->alat->kode_alat ?? '-' }} &bull; <strong>{{ $d->jumlah_pinjam }} Unit</strong></small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- E-Receipt Medical Pass Card -->
                                <div class="col-md-5 text-center">
                                    <div class="medical-pass-card shadow-sm position-relative text-start">
                                        <div class="medical-pass-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-white text-primary rounded-pill px-2 py-1 small fw-bold" style="font-size: 0.68rem;">E-KARCIS PRAKTIKUM</span>
                                                <div class="fw-bold mt-1 text-white small">{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</div>
                                            </div>
                                            <i class="bi bi-qr-code fs-3 text-white-50"></i>
                                        </div>
                                        
                                        <div class="p-3 text-center">
                                            <!-- Container QR Code -->
                                            <div class="d-flex justify-content-center my-2">
                                                <div id="qrcode-{{ $item->id }}" class="qr-code-canvas p-2 bg-white rounded-3 border shadow-xs" data-qr-text="{{ $item->kode_transaksi }}"></div>
                                            </div>
                                            
                                            <strong class="d-block text-dark fs-6 font-monospace mb-1">{{ $item->kode_transaksi }}</strong>
                                            <small class="text-muted d-block mb-3" style="font-size: 0.72rem;">Tunjukkan QR Code ini ke petugas laboran saat mengambil & mengembalikan alat.</small>
                                            
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill" onclick="window.print()">
                                                    <i class="bi bi-printer"></i> Cetak Karcis
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card border-0 rounded-4 shadow-sm p-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-search text-muted opacity-50" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Data Peminjaman Tidak Ditemukan</h5>
                    <p class="text-muted small mb-3">Tidak ditemukan transaksi dengan kata kunci: <strong>"{{ $search }}"</strong>. Pastikan Kode Transaksi atau NIM sudah benar.</p>
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
                        <p class="text-muted small mb-0">Pilih peralatan medis dari katalog dan kirimkan permohonan melalui keranjang praktikum.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Lacak Kode Transaksi</h6>
                        <p class="text-muted small mb-0">Masukkan Kode TRX atau NIM untuk mengecek persetujuan dari petugas laboran secara real-time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm p-4 h-100 text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Tunjukkan E-Receipt QR</h6>
                        <p class="text-muted small mb-0">Bawa QR Code bukti digital ke laboratorium untuk verifikasi cepat saat serah terima alat.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

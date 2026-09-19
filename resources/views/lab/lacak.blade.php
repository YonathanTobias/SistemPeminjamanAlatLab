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
                        <input type="text" name="q" class="form-control border-start-0 border-end-0" placeholder="Ketik Kode Transaksi (contoh: TRX-20260818-001) atau NIM Anda..." value="{{ $search ?? '' }}" required autofocus>
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
                        <div class="p-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%); color: white;">
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
                            <div class="mb-4 p-3 bg-light rounded-4 border">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bezier2 text-primary"></i> Progres Transaksi Peminjaman:</h6>
                                <div class="row text-center g-2 position-relative">
                                    <!-- Step 1: Pengajuan -->
                                    <div class="col-3">
                                        <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success border border-success border-opacity-25 mb-1">
                                            <i class="bi bi-send-check-fill fs-4 d-block"></i>
                                            <span class="fw-bold small">1. Diajukan</span>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Form Terkirim</small>
                                    </div>

                                    <!-- Step 2: Verifikasi -->
                                    <div class="col-3">
                                        <div class="p-2 rounded-3 {{ $item->status != 'Ditolak' ? 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border' }} mb-1">
                                            <i class="bi {{ $item->status == 'Ditolak' ? 'bi-x-circle-fill' : 'bi-shield-check' }} fs-4 d-block"></i>
                                            <span class="fw-bold small">{{ $item->status == 'Ditolak' ? '2. Ditolak' : '2. Verifikasi' }}</span>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $item->status == 'Menunggu' ? 'Sedang Diperiksa' : 'Selesai Dicek' }}</small>
                                    </div>

                                    <!-- Step 3: Pengambilan -->
                                    <div class="col-3">
                                        <div class="p-2 rounded-3 {{ in_array($item->status, ['Disetujui', 'Dikembalikan']) ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-secondary bg-opacity-10 text-muted border border-opacity-25' }} mb-1">
                                            <i class="bi bi-box-seam fs-4 d-block"></i>
                                            <span class="fw-bold small">3. Ambil di Lab</span>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $item->status == 'Disetujui' ? 'Siap Diambil' : ($item->status == 'Dikembalikan' ? 'Selesai Dipakai' : 'Menunggu Approval') }}</small>
                                    </div>

                                    <!-- Step 4: Selesai -->
                                    <div class="col-3">
                                        <div class="p-2 rounded-3 {{ $item->status == 'Dikembalikan' ? 'bg-info bg-opacity-10 text-info border border-info border-opacity-25' : 'bg-secondary bg-opacity-10 text-muted border border-opacity-25' }} mb-1">
                                            <i class="bi bi-check2-all fs-4 d-block"></i>
                                            <span class="fw-bold small">4. Selesai</span>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $item->status == 'Dikembalikan' ? 'Alat Dikembalikan' : 'Belum Kembali' }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 align-items-center">
                                <!-- Data Identitas & Paket Alat -->
                                <div class="col-md-8">
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

                                <!-- E-Receipt QR Code Card -->
                                <div class="col-md-4 text-center">
                                    <div class="p-3 bg-white border rounded-4 shadow-sm position-relative">
                                        <span class="badge bg-dark rounded-pill mb-2 px-3 py-1 text-uppercase" style="font-size: 0.7rem;">Bukti Digital (QR)</span>
                                        
                                        <!-- Container QR Code -->
                                        <div class="d-flex justify-content-center my-2">
                                            <div id="qrcode-{{ $item->id }}" class="qr-code-canvas p-2 bg-white rounded border" data-qr-text="{{ $item->kode_transaksi }}"></div>
                                        </div>
                                        
                                        <strong class="d-block text-dark small">{{ $item->kode_transaksi }}</strong>
                                        <small class="text-muted d-block mb-2" style="font-size: 0.72rem;">Tunjukkan QR Code ini ke petugas laboran saat mengambil alat.</small>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill" onclick="window.print()">
                                            <i class="bi bi-printer"></i> Cetak Bukti
                                        </button>
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

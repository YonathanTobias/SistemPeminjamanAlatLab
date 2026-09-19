@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard & Kelola Peminjaman</h2>
        <p class="text-muted small mb-0">Verifikasi pengajuan paket multi-alat, pantau masa peminjaman, dan konfirmasi pengembalian alat.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-dark shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalScanQr">
            <i class="bi bi-qr-code-scan"></i> Scan QR Pengembalian
        </button>
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill"></i> Cetak Laporan PDF
        </a>
    </div>
</div>

@if(isset($overdueLoans) && $overdueLoans->isNotEmpty())
    <!-- Peringatan Transaksi Overdue (Jatuh Tempo) -->
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-start gap-3">
        <div class="bg-danger text-white rounded-circle p-2 fs-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
            <i class="bi bi-exclamation-octagon-fill"></i>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
                <h5 class="fw-bold text-danger mb-0">Perhatian: {{ $overdueLoans->count() }} Peminjaman Melewati Batas Waktu Pengembalian (Overdue)</h5>
                <span class="badge bg-danger">Segera Hubungi Peminjam</span>
            </div>
            <p class="small text-muted mb-2">Peralatan medis di bawah ini belum dikembalikan melewati tanggal rencana kembali:</p>
            <div class="d-flex flex-wrap gap-2">
                @foreach($overdueLoans as $ol)
                    <span class="badge bg-white text-danger border border-danger border-opacity-25 py-2 px-3 shadow-xs">
                        <strong>{{ $ol->kode_transaksi }}</strong> - {{ $ol->nama_peminjam }} (NIM: {{ $ol->nim_nip }}) 
                        &bull; Jatuh Tempo: {{ \Carbon\Carbon::parse($ol->tgl_kembali_rencana)->format('d/m/Y') }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Total Transaksi</div>
                    <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Menunggu Verifikasi</div>
                    <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['menunggu'] ?? 0 }}</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Sedang Dipinjam</div>
                    <div class="fs-4 fw-bold text-primary mt-1">{{ $stats['disetujui'] ?? 0 }}</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Telah Dikembalikan</div>
                    <div class="fs-4 fw-bold text-success mt-1">{{ $stats['dikembalikan'] ?? 0 }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check2-all"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Chart.js & Analytics Section -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up text-primary"></i> Tren Peminjaman Laboratorium (7 Hari Terakhir)</h6>
            <small class="text-muted">Aktivitas peminjaman harian pada {{ $pengaturan->nama_prodi ?? 'Program Studi' }}</small>
        </div>
        <span class="badge badge-soft-primary px-3 py-1 rounded-pill small">
            <i class="bi bi-activity"></i> Live Data
        </span>
    </div>
    <div style="height: 200px; position: relative;">
        <canvas id="chartTrenMingguan"></canvas>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Cari Kode / Peminjam</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Kode Pinjam, Nama, NIM" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Tanggal Mulai Pinjam</label>
                <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="{{ request('tgl_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control form-control-sm" value="{{ request('tgl_selesai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->anyFilled(['search', 'tgl_mulai', 'tgl_selesai', 'status']))
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard2-check text-primary fs-5"></i>
            <span>Daftar Transaksi Peminjaman Praktikum</span>
        </div>
        <span class="badge badge-soft-secondary">{{ $peminjaman->total() }} Transaksi</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Kode & Peminjam</th>
                        <th style="min-width: 250px;">Paket Peralatan Dipinjam</th>
                        <th>Jadwal Peminjaman</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $index => $p)
                        @php
                            $isOverdue = in_array($p->status, ['Menunggu', 'Disetujui']) && strtotime($p->tgl_kembali_rencana) < strtotime(date('Y-m-d'));
                        @endphp
                        <tr class="{{ $isOverdue ? 'table-overdue-row' : '' }}">
                            <td class="ps-4 fw-bold text-muted">{{ $peminjaman->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge badge-soft-primary fw-bold">{{ $p->kode_transaksi ?? ('TRX-' . $p->id) }}</span>
                                    <small class="badge bg-light text-secondary border">{{ $p->prodi }}</small>
                                    @if($isOverdue)
                                        <span class="badge bg-danger badge-pulse-danger text-white py-1 px-2" style="font-size: 0.68rem;">
                                            <i class="bi bi-alarm-fill"></i> TERLAMBAT
                                        </span>
                                    @endif
                                </div>
                                <div class="fw-bold text-dark">{{ $p->nama_peminjam }}</div>
                                <div class="small text-muted">
                                    NIM/NIP: <strong>{{ $p->nim_nip }}</strong>
                                    @if($p->keperluan)
                                        &bull; <span class="text-info">{{ $p->keperluan }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1 mb-1">
                                    @foreach($p->details as $d)
                                        <span class="badge bg-white text-dark border shadow-xs py-1 px-2">
                                            <i class="bi bi-box-seam text-primary me-1"></i>
                                            {{ $d->alat->nama_alat ?? 'Alat Dihapus' }} 
                                            <strong class="text-primary">({{ $d->jumlah_pinjam }}x)</strong>
                                        </span>
                                    @endforeach
                                </div>
                                <div class="small text-muted">
                                    Total: <strong>{{ $p->details->count() }} Jenis Alat</strong> ({{ $p->details->sum('jumlah_pinjam') }} Unit)
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div class="text-muted"><i class="bi bi-calendar-arrow-right text-success"></i> {{ date('d M Y', strtotime($p->tgl_pinjam)) }}</div>
                                    <div class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}"><i class="bi bi-calendar-check {{ $isOverdue ? 'text-danger' : 'text-primary' }}"></i> {{ date('d M Y', strtotime($p->tgl_kembali_rencana)) }}</div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($p->status == 'Menunggu')
                                    <span class="badge-soft badge-soft-warning">
                                        <i class="bi bi-hourglass-split"></i> Menunggu
                                    </span>
                                @elseif($p->status == 'Disetujui')
                                    <span class="badge-soft badge-soft-primary">
                                        <i class="bi bi-check-circle-fill"></i> Dipinjam
                                    </span>
                                @elseif($p->status == 'Dikembalikan')
                                    <span class="badge-soft badge-soft-success">
                                        <i class="bi bi-check2-all"></i> Dikembalikan
                                    </span>
                                @else
                                    <span class="badge-soft badge-soft-danger">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalDetailStatus{{ $p->id }}">
                                    <i class="bi bi-gear-fill"></i> Proses
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block text-secondary opacity-50 mb-2"></i>
                                Belum ada data pengajuan peminjaman praktikum yang sesuai dengan filter.
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

<!-- Modal Detail & Proses Status Peminjaman (Diluar Tabel) -->
@foreach($peminjaman as $p)
    <div class="modal fade" id="modalDetailStatus{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.peminjaman.status', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0">
                                Verifikasi Peminjaman [{{ $p->kode_transaksi ?? ('TRX-' . $p->id) }}]
                            </h5>
                            <small class="text-muted">{{ $p->nama_peminjam }} &bull; {{ $p->nim_nip }} ({{ $p->prodi }})</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <div class="mb-3 p-3 bg-light rounded-3 border">
                            <div class="row g-2 small">
                                <div class="col-sm-6">
                                    <span class="text-muted">Keperluan:</span>
                                    <strong class="d-block text-dark">{{ $p->keperluan ?? 'Praktikum' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted">Jadwal Praktikum:</span>
                                    <strong class="d-block text-dark">{{ $p->tgl_pinjam }} s/d {{ $p->tgl_kembali_rencana }}</strong>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-2">Rincian Paket Alat yang Diajukan:</h6>
                        <div class="table-responsive border rounded-3 mb-3">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Peralatan</th>
                                        <th class="text-center">Kondisi</th>
                                        <th class="text-center">Stok Tersedia</th>
                                        <th class="text-center">Jumlah Pinjam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p->details as $d)
                                        <tr>
                                            <td><span class="badge badge-soft-primary">{{ $d->alat->kode_alat ?? '-' }}</span></td>
                                            <td class="fw-bold">{{ $d->alat->nama_alat ?? 'Alat Dihapus' }}</td>
                                            <td class="text-center"><span class="badge-soft badge-soft-success">{{ $d->alat->kondisi ?? '-' }}</span></td>
                                            <td class="text-center">
                                                <span class="fw-bold {{ ($d->alat->stok_tersedia ?? 0) >= $d->jumlah_pinjam ? 'text-success' : 'text-danger' }}">
                                                    {{ $d->alat->stok_tersedia ?? 0 }} Unit
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6">{{ $d->jumlah_pinjam }} Unit</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Ubah Status Transaksi:</label>
                            <select name="status" class="form-select" required>
                                <option value="Menunggu" {{ $p->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu Konfirmasi</option>
                                <option value="Disetujui" {{ $p->status == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui (Kurangi Stok Alat)</option>
                                <option value="Dikembalikan" {{ $p->status == 'Dikembalikan' ? 'selected' : '' }}>📦 Dikembalikan (Kembalikan Stok Alat)</option>
                                <option value="Ditolak" {{ $p->status == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label small text-muted">Catatan Laboran (Opsional):</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Tuliskan catatan kondisi alat atau alasan jika ditolak...">{{ $p->catatan }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save"></i> Simpan Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- MODAL SCANNER QR CODE PENGEMBALIAN / VERIFIKASI -->
<div class="modal fade" id="modalScanQr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-qr-code-scan me-1"></i> Scan QR Code Bukti Mahasiswa
                    </h5>
                    <small class="text-white-50">Arahkan kamera ke QR Code transaksi peminjaman</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopQrScanner()"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="qr-reader" class="rounded-3 overflow-hidden border mb-3" style="width: 100%; min-height: 250px; background: #0f172a;"></div>
                <div id="qr-reader-results" class="fw-bold text-success mb-2"></div>
                
                <div class="p-3 bg-light rounded-3 border">
                    <label class="form-label small text-muted mb-1">Atau Masukkan Kode Transaksi Manual:</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="manualTrxInput" class="form-control" placeholder="Contoh: {{ $pengaturan->prodi_prefix ?? 'LAB' }}-{{ date('Ymd') }}-001">
                        <button class="btn btn-primary" type="button" onclick="submitManualTrx('{{ route('admin.peminjaman.index') }}')">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" onclick="stopQrScanner()">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Charts
        initDashboardCharts(
            {!! json_encode($weeklyLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            {!! json_encode($weeklyData ?? [0, 0, 0, 0, 0, 0, 0]) !!}
        );

        // Init QR Scanner
        initQrScanner("{{ route('admin.peminjaman.index') }}");
    });
</script>
@endpush
@endsection
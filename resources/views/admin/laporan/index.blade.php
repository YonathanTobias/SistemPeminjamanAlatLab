@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Laporan & Rekap Peminjaman</h2>
        <p class="text-muted small mb-0">Rekapitulasi data transaksi peminjaman alat {{ $pengaturan->unit_laboratorium ?? 'Laboratorium' }} {{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya' }}.</p>
    </div>
    <div>
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger shadow-sm px-4">
            <i class="bi bi-file-earmark-pdf-fill"></i> Cetak Laporan PDF
        </a>
    </div>
</div>

<!-- Stat KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
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
        <div class="stat-card">
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
        <div class="stat-card">
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
        <div class="stat-card">
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

<!-- Filter Card -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
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
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Data Table Card -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary fs-5"></i>
            <span>Tabel Rekap Transaksi Peminjaman</span>
        </div>
        <span class="badge badge-soft-secondary">{{ $peminjaman->total() }} Transaksi</span>
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
                        <th style="min-width: 280px;">Rincian Paket Peralatan</th>
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
                                <small class="text-muted">{{ $item->details->count() }} Jenis Alat</small>
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
                                    <span class="badge-soft badge-soft-primary">
                                        <i class="bi bi-check-circle-fill"></i> Dipinjam
                                    </span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="badge-soft badge-soft-danger">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    </span>
                                @elseif($item->status == 'Dikembalikan')
                                    <span class="badge-soft badge-soft-success">
                                        <i class="bi bi-check2-all"></i> Dikembalikan
                                    </span>
                                @else
                                    <span class="badge-soft badge-soft-warning">
                                        <i class="bi bi-hourglass-split"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="mb-2">
                                    <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Tidak Ada Data Laporan</h6>
                                <p class="small text-muted mb-0">Sesuaikan kriteria filter tanggal dan status di atas.</p>
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
@endsection

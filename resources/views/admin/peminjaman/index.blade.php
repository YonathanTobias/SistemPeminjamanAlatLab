@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Kelola Peminjaman Alat Praktikum</h2>
        <p class="text-muted small mb-0">Verifikasi pengajuan paket multi-alat, pantau masa peminjaman, dan konfirmasi pengembalian alat.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill"></i> Cetak Laporan PDF
        </a>
    </div>
</div>

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

<!-- Filter Toolbar -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Cari Kode / Peminjam</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="TRX-..., Nama, NIM" value="{{ request('search') }}">
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
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $peminjaman->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge badge-soft-primary fw-bold">{{ $p->kode_transaksi ?? ('TRX-' . $p->id) }}</span>
                                    <small class="badge bg-light text-secondary border">{{ $p->prodi }}</small>
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
                                    <div class="text-muted"><i class="bi bi-calendar-check text-danger"></i> {{ date('d M Y', strtotime($p->tgl_kembali_rencana)) }}</div>
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

                        <!-- Modal Detail & Proses Status Peminjaman -->
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

                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
@endsection
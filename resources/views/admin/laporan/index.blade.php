@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Laporan & Rekap Peminjaman</h2>
        <p class="text-muted small mb-0">Rekapitulasi data transaksi peminjaman alat laboratorium Keperawatan STIKES Panti Waluya Malang.</p>
    </div>
    <div>
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill"></i> Cetak Laporan PDF
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-calendar3 text-primary"></i> Periode Mulai Pinjam
                </label>
                <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-calendar-check text-primary"></i> Periode Selesai Pinjam
                </label>
                <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-funnel text-primary"></i> Filter Status
                </label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>🔄 Dikembalikan</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-filter"></i> Filter Data
                </button>
                @if(request()->hasAny(['tgl_mulai', 'tgl_selesai', 'status']))
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Data Table Card -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden">
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
                        <th>Kode Transaksi</th>
                        <th>Peminjam (NIM - Prodi)</th>
                        <th>Keperluan</th>
                        <th>Daftar Peralatan Medis</th>
                        <th class="text-center">Total Unit</th>
                        <th>Tgl Pinjam - Kembali</th>
                        <th class="text-center pe-4">Status</th>
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
                                <small class="text-muted">{{ $item->nim_nip }} &bull; {{ $item->prodi }}</small>
                            </td>
                            <td>
                                <span class="small text-dark">{{ $item->keperluan ?? 'Praktikum' }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($item->details as $d)
                                        <div class="small">
                                            &bull; {{ $d->alat->nama_alat ?? 'Alat Dihapus' }} 
                                            <span class="badge bg-light text-dark border">({{ $d->jumlah_pinjam }}x)</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge bg-primary fs-6">{{ $item->details->sum('jumlah_pinjam') }}</span>
                            </td>
                            <td>
                                <div class="small">
                                    <div><i class="bi bi-calendar-event text-success"></i> {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</div>
                                    <div class="text-muted"><i class="bi bi-calendar-check text-danger"></i> {{ \Carbon\Carbon::parse($item->tgl_kembali_rencana)->format('d/m/Y') }}</div>
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

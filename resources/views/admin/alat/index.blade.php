@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Inventaris Alat Laboratorium</h2>
        <p class="text-muted small mb-0">Manajemen master data peralatan medis, kode identifikasi, dan ketersediaan unit.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
            <i class="bi bi-plus-lg"></i> Tambah Alat Baru
        </button>
    </div>
</div>

<!-- KPI Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-boxes"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Jenis Alat</div>
                <div class="fs-4 fw-bold text-dark">{{ $stats['total_jenis'] ?? $alats->total() }} Jenis</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-layers"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Fisik Unit</div>
                <div class="fs-4 fw-bold text-primary">{{ $stats['total_stok'] ?? 0 }} Unit</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Unit Siap Pakai</div>
                <div class="fs-4 fw-bold text-success">{{ $stats['stok_tersedia'] ?? 0 }} Unit</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Kondisi Baik</div>
                <div class="fs-4 fw-bold text-warning">{{ $stats['kondisi_baik'] ?? 0 }} Jenis</div>
            </div>
        </div>
    </div>
</div>

<!-- Form Cari Alat -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.alat.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan nama alat medis atau kode (contoh: LAB-001, Mikroskop)..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Alat -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-box-seam text-primary fs-5"></i>
            <span>Daftar Inventaris Alat Laboratorium</span>
        </div>
        <span class="badge badge-soft-secondary">{{ $alats->total() }} Data Terdaftar</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode Alat</th>
                        <th>Nama Peralatan Lab</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Stok Tersedia</th>
                        <th class="text-center">Stok Total</th>
                        <th>Status Ketersediaan</th>
                        <th class="text-center pe-4" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alats as $alat)
                        <tr>
                            <td class="ps-4">
                                <span class="badge badge-soft-primary fw-bold">
                                    <i class="bi bi-tag-fill"></i> {{ $alat->kode_alat }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $alat->nama_alat }}</div>
                                <small class="text-muted">Laboratorium STIKES Panti Waluya</small>
                            </td>
                            <td class="text-center">
                                @if($alat->kondisi == 'Baik')
                                    <span class="badge-soft badge-soft-success">
                                        <i class="bi bi-check2"></i> Baik
                                    </span>
                                @else
                                    <span class="badge-soft badge-soft-warning">
                                        <i class="bi bi-exclamation-triangle"></i> {{ $alat->kondisi }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-bold fs-6 {{ $alat->stok_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $alat->stok_tersedia }} Unit
                                </span>
                            </td>
                            <td class="text-center text-muted fw-semibold">{{ $alat->stok_total }} Unit</td>
                            <td style="min-width: 140px;">
                                @php
                                    $pct = $alat->stok_total > 0 ? ($alat->stok_tersedia / $alat->stok_total) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar {{ $alat->stok_tersedia > 0 ? 'bg-success' : 'bg-danger' }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <small class="text-muted fw-bold" style="font-size: 0.75rem;">{{ round($pct) }}%</small>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alat {{ $alat->nama_alat }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Hapus Alat">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-2">
                                    <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Belum Ada Data Alat</h6>
                                <p class="small text-muted mb-0">Klik tombol "+ Tambah Alat Baru" di kanan atas untuk menambahkan peralatan medis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($alats->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center border-top">
            {{ $alats->links() }}
        </div>
    @endif
</div>

<!-- Modal Tambah Alat Lab -->
<div class="modal fade" id="modalTambahAlat" tabindex="-1" aria-labelledby="modalTambahAlatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.alat.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark" id="modalTambahAlatLabel">
                        <i class="bi bi-plus-circle text-primary"></i> Tambah Peralatan Lab Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-tag text-primary"></i> Kode Alat Lab
                            </label>
                            <input type="text" name="kode_alat" class="form-control" placeholder="Contoh: LAB-001" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Harus unik untuk setiap jenis alat</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-activity text-primary"></i> Kondisi Alat
                            </label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-heading text-primary"></i> Nama Peralatan Medis / Lab
                            </label>
                            <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Stetoskop Littmann Classic III" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-box text-primary"></i> Jumlah Stok Total Unit
                            </label>
                            <input type="number" name="stok_total" class="form-control" min="1" value="1" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Stok tersedia otomatis disamakan dengan stok total saat pertama dibuat.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg"></i> Simpan Alat Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
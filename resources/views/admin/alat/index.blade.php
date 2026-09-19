@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Inventaris Alat Laboratorium</h2>
        <p class="text-muted small mb-0">Manajemen master data peralatan medis, kategori praktikum, foto alat, dan ketersediaan unit.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
            <i class="bi bi-plus-lg"></i> + Tambah Alat Baru
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

<!-- Kategori Filter Pill Tabs -->
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 overflow-auto pb-2" style="white-space: nowrap;">
        <span class="text-muted small fw-bold me-2"><i class="bi bi-funnel"></i> Kategori:</span>
        <a href="{{ route('admin.alat.index', request()->except('kategori', 'page')) }}" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold {{ !request('kategori') ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-white' }}">
            Semua Kategori
        </a>
        @foreach($kategoriList as $kat)
            <a href="{{ route('admin.alat.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat])) }}" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold {{ request('kategori') == $kat ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-white' }}">
                {{ $kat }}
            </a>
        @endforeach
    </div>
</div>

<!-- Form Cari Alat -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin.alat.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-10">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan nama alat medis atau kode (contoh: KDM-001, Stetoskop)..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
                @if(request()->anyFilled(['search', 'kategori']))
                    <a href="{{ route('admin.alat.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Alat -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
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
                        <th class="ps-4" style="width: 70px;">Foto</th>
                        <th>Kode Alat</th>
                        <th>Nama Peralatan & Kategori</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Stok Tersedia</th>
                        <th class="text-center">Stok Total</th>
                        <th>Ketersediaan Unit</th>
                        <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alats as $alat)
                        <tr>
                            <td class="ps-4">
                                <div class="rounded-3 overflow-hidden bg-light border d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px;">
                                    @if($alat->gambar && file_exists(public_path($alat->gambar)))
                                        <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <i class="bi bi-box-seam text-primary fs-5"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary fw-bold">
                                    <i class="bi bi-tag-fill"></i> {{ $alat->kode_alat }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $alat->nama_alat }}</div>
                                <span class="badge bg-light text-secondary border small" style="font-size: 0.72rem;">
                                    {{ $alat->kategori ?? 'KDM & Tanda Vital' }}
                                </span>
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
                            <td style="min-width: 130px;">
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
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalEditAlat{{ $alat->id }}" title="Edit Alat">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alat {{ $alat->nama_alat }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2 shadow-xs" title="Hapus Alat">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
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
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalTambahAlatLabel">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Peralatan Lab Baru
                        </h5>
                        <small class="text-white-50">Daftarkan inventaris alat medis baru ke sistem</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-tag text-primary"></i> Kode Alat Lab
                            </label>
                            <input type="text" name="kode_alat" class="form-control" placeholder="Contoh: KDM-010" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-bookmark text-primary"></i> Kategori Praktikum
                            </label>
                            <select name="kategori" class="form-select" required>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-card-heading text-primary"></i> Nama Peralatan Medis / Lab
                            </label>
                            <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Stetoskop Littmann Classic III" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-box text-primary"></i> Stok Total Unit
                            </label>
                            <input type="number" name="stok_total" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-activity text-primary"></i> Kondisi Fisik
                            </label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-image text-primary"></i> Foto Alat Medis (Opsional)
                            </label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            <small class="text-muted" style="font-size: 0.75rem;">Format: JPG, PNG, WEBP (Maksimal 2MB).</small>
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

<!-- Modal Edit Alat Lab per Item (Diluar Tabel) -->
@foreach($alats as $alat)
    <div class="modal fade" id="modalEditAlat{{ $alat->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0f766e 100%);">
                        <div>
                            <h5 class="modal-title fw-bold mb-0">
                                <i class="bi bi-pencil-square me-1"></i> Edit Alat [{{ $alat->kode_alat }}]
                            </h5>
                            <small class="text-white-50">Perbarui rincian spesifikasi atau foto alat</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Kode Alat</label>
                                <input type="text" name="kode_alat" class="form-control" value="{{ old('kode_alat', $alat->kode_alat) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Kategori Praktikum</label>
                                <select name="kategori" class="form-select" required>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat }}" {{ $alat->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Nama Peralatan Medis</label>
                                <input type="text" name="nama_alat" class="form-control" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Stok Total Unit</label>
                                <input type="number" name="stok_total" class="form-control" min="1" value="{{ old('stok_total', $alat->stok_total) }}" required>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Stok tersedia saat ini: <strong>{{ $alat->stok_tersedia }} Unit</strong></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Kondisi</label>
                                <select name="kondisi" class="form-select">
                                    <option value="Baik" {{ $alat->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ $alat->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Ganti Foto Alat (Opsional)</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                @if($alat->gambar && file_exists(public_path($alat->gambar)))
                                    <small class="text-success d-block mt-1"><i class="bi bi-image"></i> Foto saat ini terpasang</small>
                                @endif
                            </div>
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
@endforeach
@endsection

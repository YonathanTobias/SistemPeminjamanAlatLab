@extends('layouts.app')

@section('content')
<div class="container py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Inventaris Alat Laboratorium</h3>
            <p class="text-muted small mb-0">Kelola ketersediaan dan kondisi seluruh alat lab</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
            + Tambah Alat Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Cari Alat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.alat.index') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode alat..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Alat -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode Alat</th>
                            <th>Nama Alat</th>
                            <th>Kondisi</th>
                            <th>Stok Tersedia</th>
                            <th>Stok Total</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alats as $alat)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $alat->kode_alat }}</td>
                                <td>{{ $alat->nama_alat }}</td>
                                <td>
                                    <span class="badge {{ $alat->kondisi == 'Baik' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $alat->kondisi }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold {{ $alat->stok_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $alat->stok_tersedia }} Unit
                                    </span>
                                </td>
                                <td>{{ $alat->stok_total }} Unit</td>
                                <td class="text-center pe-3">
                                    <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus alat ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data alat lab.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($alats->hasPages())
            <div class="card-footer bg-white">
                {{ $alats->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Alat Lab -->
<div class="modal fade" id="modalTambahAlat" tabindex="-1" aria-labelledby="modalTambahAlatLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.alat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAlatLabel">Tambah Alat Lab Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Alat</label>
                        <input type="text" name="kode_alat" class="form-control" placeholder="LAB-001" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kondisi Alat</label>
                        <select name="kondisi" class="form-select">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Alat Lab</label>
                        <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Stetoskop" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jumlah Stok Total</label>
                        <input type="number" name="stok_total" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Alat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
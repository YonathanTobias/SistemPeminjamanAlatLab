@extends('layouts.app')

@section('content')
<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-building text-primary me-2"></i> Kelola Data Master Ruangan Lab</h3>
            <p class="text-muted small mb-0">Atur ketersediaan dan fasilitas ruang laboratorium STIKES.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRuangan">
            <i class="bi bi-plus-circle me-1"></i> Tambah Ruangan Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-3" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Data Ruangan -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Ruangan</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Fasilitas</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ruangans as $ruang)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $ruang->kode_ruangan }}</td>
                            <td class="fw-semibold">{{ $ruang->nama_ruangan }}</td>
                            <td>{{ $ruang->lokasi ?? '-' }}</td>
                            <td>{{ $ruang->kapasitas }} Orang</td>
                            <td>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                    {{ $ruang->fasilitas ?? '-' }}
                                </small>
                            </td>
                            <td>
                                @if ($ruang->status === 'Tersedia')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Tersedia</span>
                                @elseif ($ruang->status === 'Perbaikan')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">Perbaikan</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-1">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEditRuangan{{ $ruang->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.ruangan.destroy', $ruang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Edit Ruangan -->
                                <div class="modal fade text-start" id="modalEditRuangan{{ $ruang->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <form action="{{ route('admin.ruangan.update', $ruang->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">Edit Data Ruangan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Kode Ruangan</label>
                                                        <input type="text" name="kode_ruangan" class="form-control rounded-3" value="{{ $ruang->kode_ruangan }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Nama Ruangan</label>
                                                        <input type="text" name="nama_ruangan" class="form-control rounded-3" value="{{ $ruang->nama_ruangan }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Lokasi / Gedung</label>
                                                        <input type="text" name="lokasi" class="form-control rounded-3" value="{{ $ruang->lokasi }}">
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Kapasitas (Orang)</label>
                                                            <input type="number" name="kapasitas" class="form-control rounded-3" value="{{ $ruang->kapasitas }}" min="0" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Status</label>
                                                            <select name="status" class="form-select rounded-3" required>
                                                                <option value="Tersedia" {{ $ruang->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                                <option value="Perbaikan" {{ $ruang->status == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                                <option value="Tidak Aktif" {{ $ruang->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Fasilitas</label>
                                                        <textarea name="fasilitas" rows="3" class="form-control rounded-3">{{ $ruang->fasilitas }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data ruangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $ruangans->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Ruangan -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.ruangan.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Ruangan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Ruangan</label>
                        <input type="text" name="kode_ruangan" class="form-control rounded-3" placeholder="Contoh: LAB-KEP-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" class="form-control rounded-3" placeholder="Contoh: Laboratorium Keperawatan Medikal Bedah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi / Gedung</label>
                        <input type="text" name="lokasi" class="form-control rounded-3" placeholder="Contoh: Gedung A Lantai 2">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kapasitas (Orang)</label>
                            <input type="number" name="kapasitas" class="form-control rounded-3" value="30" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select rounded-3" required>
                                <option value="Tersedia" selected>Tersedia</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fasilitas</label>
                        <textarea name="fasilitas" rows="3" class="form-control rounded-3" placeholder="Contoh: Bed Pasien, Tiang Infus, AC, LCD Proyektor"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

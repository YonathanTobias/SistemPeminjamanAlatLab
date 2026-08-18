@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard Laboratorium STIKES</h2>
        <p class="text-muted small mb-0">Pusat kontrol inventaris alat dan persetujuan peminjaman mahasiswa/dosen.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
            <i class="bi bi-plus-lg"></i> Tambah Alat Baru
        </button>
        <a href="{{ route('admin.laporan.pdf') }}" target="_blank" class="btn btn-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill"></i> Cetak Laporan PDF
        </a>
    </div>
</div>

<!-- 1. TABEL DAFTAR ALAT LAB (INVENTARIS) -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-box-seam text-primary fs-5"></i>
            <span>Daftar Inventaris Alat Lab</span>
        </div>
        <span class="badge badge-soft-primary">{{ $alats->count() }} Jenis Alat Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode Alat</th>
                        <th>Nama Peralatan</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Stok Tersedia</th>
                        <th class="text-center">Stok Total</th>
                        <th class="text-center pe-4" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alats as $alat)
                        <tr>
                            <td class="ps-4">
                                <span class="badge badge-soft-primary fw-bold">{{ $alat->kode_alat }}</span>
                            </td>
                            <td class="fw-bold text-dark">{{ $alat->nama_alat }}</td>
                            <td class="text-center">
                                @if($alat->kondisi == 'Baik')
                                    <span class="badge-soft badge-soft-success">Baik</span>
                                @else
                                    <span class="badge-soft badge-soft-warning">{{ $alat->kondisi }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-bold {{ $alat->stok_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $alat->stok_tersedia }} Unit
                                </span>
                            </td>
                            <td class="text-center text-muted">{{ $alat->stok_total }} Unit</td>
                            <td class="text-center pe-4">
                                <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data alat laboratorium.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 2. TABEL DAFTAR PENGAJUAN PEMINJAMAN ALAT -->
<div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check text-primary fs-5"></i>
            <span>Daftar Pengajuan Peminjaman Terbaru</span>
        </div>
        <span class="badge badge-soft-secondary">{{ $peminjamans->total() }} Data Peminjaman</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Peminjam</th>
                        <th>Alat Dipinjam</th>
                        <th class="text-center">Jml</th>
                        <th>Jadwal Peminjaman</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi Laboran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $p)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $peminjamans->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold d-block text-dark">{{ $p->nama_peminjam }}</span>
                                <small class="text-muted">{{ $p->nim_nip }} &bull; {{ $p->prodi }}</small>
                            </td>
                            <td>{{ $p->alat->nama_alat ?? 'Alat Dihapus' }}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $p->jumlah_pinjam }} Unit</span></td>
                            <td><small class="text-muted">{{ $p->tgl_pinjam }} s/d {{ $p->tgl_kembali_rencana }}</small></td>
                            <td class="text-center">
                                @if($p->status == 'Menunggu')
                                    <span class="badge-soft badge-soft-warning">Menunggu</span>
                                @elseif($p->status == 'Disetujui')
                                    <span class="badge-soft badge-soft-primary">Disetujui</span>
                                @elseif($p->status == 'Dikembalikan')
                                    <span class="badge-soft badge-soft-success">Dikembalikan</span>
                                @else
                                    <span class="badge-soft badge-soft-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($p->status == 'Menunggu')
                                    <form action="{{ route('admin.peminjaman.status', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Disetujui">
                                        <button class="btn btn-sm btn-success rounded-pill px-3 me-1" onclick="return confirm('Setujui peminjaman ini?')">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.peminjaman.status', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                                    </form>
                                @elseif($p->status == 'Disetujui')
                                    <form action="{{ route('admin.peminjaman.status', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Dikembalikan">
                                        <button class="btn btn-sm btn-info text-white rounded-pill px-3" onclick="return confirm('Konfirmasi pengembalian alat?')">Tandai Dikembalikan</button>
                                    </form>
                                @else
                                    <span class="text-muted small">- Selesai -</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada pengajuan peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($peminjamans->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center border-top">
            {{ $peminjamans->links() }}
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
                    <h5 class="modal-title fw-bold text-dark" id="modalTambahAlatLabel">Tambah Alat Lab Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 row g-3">
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
                    <div class="col-12">
                        <label class="form-label">Nama Alat Lab</label>
                        <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Stetoskop" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Jumlah Stok Total</label>
                        <input type="number" name="stok_total" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Alat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
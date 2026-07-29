@extends('layouts.app')

@section('content')
<div class="container py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Kelola Laboratorium STIKES</h3>
            <p class="text-muted small mb-0">Manajemen Inventaris Alat & Persetujuan Peminjaman</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
            + Tambah Alat Lab Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 1. TABEL DAFTAR ALAT LAB (INVENTARIS) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
            <span>Daftar Inventaris Alat Lab</span>
            <span class="badge bg-primary">{{ $alats->count() }} Jenis Alat</span>
        </div>
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
                                    <form action="{{ route('admin.lab.alat.destroy', $alat->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus alat ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data alat laboratorium. Klik tombol "+ Tambah Alat Lab Baru" untuk menambah alat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. TABEL DAFTAR PENGAJUAN PEMINJAMAN ALAT -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold py-3">Daftar Pengajuan Peminjaman Alat</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Peminjam (NIM/NIP)</th>
                            <th>Alat Dipinjam</th>
                            <th>Jml</th>
                            <th>Tgl Pinjam - Kembali</th>
                            <th>Status</th>
                            <th class="text-center pe-3">Aksi Laboran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $index => $p)
                            <tr>
                                <td class="ps-3">{{ $peminjamans->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-bold d-block">{{ $p->nama_peminjam }}</span>
                                    <small class="text-muted">{{ $p->nim_nip }} ({{ $p->prodi }})</small>
                                </td>
                                <td>{{ $p->alat->nama_alat ?? 'Alat Dihapus' }}</td>
                                <td><span class="badge bg-secondary">{{ $p->jumlah_pinjam }} Unit</span></td>
                                <td><small>{{ $p->tgl_pinjam }} s/d {{ $p->tgl_kembali_rencana }}</small></td>
                                <td>
                                    @if($p->status == 'Menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($p->status == 'Disetujui')
                                        <span class="badge bg-primary">Disetujui</span>
                                    @elseif($p->status == 'Dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    @if($p->status == 'Menunggu')
                                        <form action="{{ route('admin.lab.peminjaman.status', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button class="btn btn-sm btn-success me-1" onclick="return confirm('Setujui peminjaman ini?')">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.lab.peminjaman.status', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                                        </form>
                                    @elseif($p->status == 'Disetujui')
                                        <form action="{{ route('admin.lab.peminjaman.status', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Dikembalikan">
                                            <button class="btn btn-sm btn-info text-white" onclick="return confirm('Konfirmasi bahwa alat sudah dikembalikan?')">Tandai Dikembalikan</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">- Selesai -</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada pengajuan peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($peminjamans->hasPages())
            <div class="card-footer bg-white">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Alat Lab -->
<div class="modal fade" id="modalTambahAlat" tabindex="-1" aria-labelledby="modalTambahAlatLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.lab.alat.store') }}" method="POST">
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
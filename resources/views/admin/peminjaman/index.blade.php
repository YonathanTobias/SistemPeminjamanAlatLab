@extends('layouts.app') {{-- Sesuaikan dengan nama layout admin kamu --}}

@section('content')
<div class="container my-4">
    
    <!-- Header & Alert Notification -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Kelola Peminjaman Alat Lab</h2>
            <p class="text-muted small">Daftar permohonan dan riwayat peminjaman alat laboratorium</p>
        </div>
        
        <!-- Tombol Cetak PDF -->
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF / Laporan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Filter Data -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Tgl Pinjam Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Tgl Pinjam Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>NIM/NIP</th>
                            <th>Prodi</th>
                            <th>Alat Lab</th>
                            <th>Jumlah</th>
                            <th>Tgl Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $index => $item)
                            <tr>
                                <td class="text-center">{{ $peminjaman->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $item->nama_peminjam }}</strong>
                                </td>
                                <td>{{ $item->nim_nip }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>
                                    {{ $item->alat->nama_alat ?? 'Alat Dihapus' }}
                                    <br>
                                    <small class="text-muted">Kode: {{ $item->alat->kode_alat ?? '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $item->jumlah_pinjam }}</span>
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_kembali_rencana)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($item->status == 'Disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($item->status == 'Ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($item->status == 'Dikembalikan')
                                        <span class="badge bg-info">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Tombol Trigger Modal Edit Status -->
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $item->id }}">
                                        Ubah Status
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Ubah Status -->
                            <div class="modal fade" id="modalStatus{{ $item->id }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('admin.peminjaman.status', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalStatusLabel{{ $item->id }}">Ubah Status Peminjaman</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Peminjam:</label>
                                                    <h6>{{ $item->nama_peminjam }} ({{ $item->nim_nip }})</h6>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alat yang Dipinjam:</label>
                                                    <h6>{{ $item->alat->nama_alat ?? '-' }} (Jumlah: {{ $item->jumlah_pinjam }})</h6>
                                                    <small class="text-info">Stok Tersedia Saat Ini: {{ $item->alat->stok_tersedia ?? 0 }}</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status" class="form-label font-weight-bold">Status Peminjaman</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Menunggu" {{ $item->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                        <option value="Disetujui" {{ $item->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (Potong Stok)</option>
                                                        <option value="Dikembalikan" {{ $item->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan (Kembalikan Stok)</option>
                                                        <option value="Ditolak" {{ $item->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="catatan" class="form-label">Catatan Admin (Opsional)</label>
                                                    <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan alasan jika ditolak atau catatan tambahan...">{{ $item->catatan }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">Belum ada data peminjaman alat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $peminjaman->links() }}
        </div>
    </div>
</div>
@endsection
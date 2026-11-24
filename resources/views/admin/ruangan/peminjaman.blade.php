@extends('layouts.app')

@section('content')
<div class="container-fluid px-lg-4 py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-door-open text-primary me-2"></i> Kelola Peminjaman Ruangan Lab</h3>
            <p class="text-muted small mb-0">Verifikasi pengajuan penggunaan ruangan laboratorium dari mahasiswa dan dosen.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.ruangan.peminjaman.pdf', request()->query()) }}" target="_blank" class="btn btn-danger rounded-pill px-3.5 btn-sm shadow-xs fw-bold">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
            </a>
            <a href="{{ route('admin.ruangan.index') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm">
                <i class="bi bi-building me-1"></i> Data Master Ruangan
            </a>
        </div>
    </div>

    <!-- Switch/Tab Pill Bersebelahan Layanan Peminjaman Admin -->
    <div class="d-flex mb-4">
        <div class="p-1 rounded-pill bg-white border shadow-sm d-inline-flex gap-1" style="max-width: 100%; overflow-x: auto;">
            <a href="{{ route('admin.peminjaman.index') }}" class="btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 btn-light text-muted border-0 bg-transparent">
                <i class="bi bi-box-seam"></i>
                <span>Peminjaman Alat Lab</span>
            </a>
            <a href="{{ route('admin.ruangan.peminjaman') }}" class="btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 btn-primary text-white shadow-xs">
                <i class="bi bi-door-open-fill"></i>
                <span>Peminjaman Ruangan Lab</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter & Pencarian -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.ruangan.peminjaman') }}" method="GET" class="row g-2.5 align-items-center">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <select name="tahun" class="form-select rounded-pill fw-semibold" onchange="this.form.submit()" title="Filter Berdasarkan Tahun Transaksi">
                        <option value="{{ date('Y') }}" {{ ($tahunDipilih ?? date('Y')) == date('Y') ? 'selected' : '' }}>{{ date('Y') }} (Tahun Ini)</option>
                        @if(isset($yearsList))
                            @foreach($yearsList as $yr)
                                @if($yr != date('Y'))
                                    <option value="{{ $yr }}" {{ ($tahunDipilih ?? '') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                                @endif
                            @endforeach
                        @endif
                        <option value="semua" {{ ($tahunDipilih ?? '') === 'semua' ? 'selected' : '' }}>Semua Arsip</option>
                    </select>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-8 col-sm-6">
                    <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari Kode Transaksi, Nama, atau NIM/NIP..." value="{{ request('search') }}">
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <select name="status" class="form-select rounded-pill">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1 shadow-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if (request('search') || request('status') || request('tahun') === 'semua' || (request('tahun') && request('tahun') != date('Y')))
                        <a href="{{ route('admin.ruangan.peminjaman') }}" class="btn btn-light border rounded-pill px-3" title="Reset ke Default Tahun Ini">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Peminjaman Ruangan -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-door-open text-primary fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Daftar Peminjaman Ruang Laboratorium</h6>
                @if(($tahunDipilih ?? '') === 'semua')
                    <span class="badge bg-secondary border">Arsip: Semua Tahun</span>
                @else
                    <span class="badge bg-primary-subtle text-primary border">Tahun {{ $tahunDipilih ?? date('Y') }}</span>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.ruangan.peminjaman.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3 shadow-xs fw-bold" title="Cetak Rekapitulasi PDF">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                </a>
                <span class="badge badge-soft-secondary font-monospace">{{ $peminjamans->total() }} Transaksi</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Transaksi</th>
                        <th>Ruangan</th>
                        <th>Peminjam</th>
                        <th>Jadwal</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamans as $pinjam)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $pinjam->kode_transaksi }}</td>
                            <td>
                                <div class="fw-semibold">{{ $pinjam->ruangan->nama_ruangan ?? '-' }}</div>
                                <small class="text-muted">{{ $pinjam->ruangan->kode_ruangan ?? '' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $pinjam->nama_peminjam }}</div>
                                <small class="text-muted">{{ $pinjam->nim_nip }} ({{ $pinjam->prodi }})</small>
                            </td>
                            <td>
                                <div><i class="bi bi-calendar3 me-1 text-muted"></i> {{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->translatedFormat('d M Y') }}</div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ substr($pinjam->jam_mulai, 0, 5) }} - {{ substr($pinjam->jam_selesai, 0, 5) }}</small>
                            </td>
                            <td>
                                <span>{{ $pinjam->keperluan }}</span>
                                @if($pinjam->catatan)
                                    <div class="small text-muted fst-italic">Note: {{ $pinjam->catatan }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($pinjam->status === 'Menunggu')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">Menunggu</span>
                                @elseif ($pinjam->status === 'Disetujui')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Disetujui</span>
                                @elseif ($pinjam->status === 'Selesai')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">Selesai</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $pinjam->id }}">
                                    Ubah Status
                                </button>

                                <!-- Modal Ubah Status -->
                                <div class="modal fade text-start" id="modalStatus{{ $pinjam->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <form action="{{ route('admin.ruangan.peminjaman.status', $pinjam->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">Update Status Peminjaman</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p class="mb-3 text-muted">Kode: <strong>{{ $pinjam->kode_transaksi }}</strong> - {{ $pinjam->nama_peminjam }}</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Pilih Status</label>
                                                        <select name="status" class="form-select rounded-3" required>
                                                            <option value="Menunggu" {{ $pinjam->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                            <option value="Disetujui" {{ $pinjam->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                            <option value="Selesai" {{ $pinjam->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                            <option value="Ditolak" {{ $pinjam->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Catatan Laboran</label>
                                                        <textarea name="catatan" rows="3" class="form-control rounded-3" placeholder="Alasan penolakan / instruksi penggunaan ruangan...">{{ $pinjam->catatan }}</textarea>
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
                                Belum ada pengajuan peminjaman ruangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
@endsection

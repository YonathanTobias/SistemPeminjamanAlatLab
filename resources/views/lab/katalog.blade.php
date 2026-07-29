@extends('layouts.app')

@section('content')
<div class="container py-2">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Katalog & Peminjaman Alat Lab</h2>
        <p class="text-muted">Laboratorium STIKES Panti Waluya Malang</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Cari Alat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('lab.katalog') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama Alat atau Kode..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cari Alat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Grid Katalog Alat -->
    <div class="row g-4">
        @forelse($alats as $alat)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold text-dark">{{ $alat->nama_alat }}</h5>
                            <p class="text-muted small mb-3">Kode: {{ $alat->kode_alat }}</p>
                        </div>
                        
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="small text-muted">Stok Tersedia:</span>
                                <span class="badge {{ $alat->stok_tersedia > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $alat->stok_tersedia }} / {{ $alat->stok_total }} Unit
                                </span>
                            </div>

                            @if($alat->stok_tersedia > 0)
                                <button type="button" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalPinjam{{ $alat->id }}">
                                    Ajukan Peminjaman
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Form Pinjam (1 Modal per Alat) -->
            <div class="modal fade" id="modalPinjam{{ $alat->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $alat->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('lab.pinjam.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="alat_lab_id" value="{{ $alat->id }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel{{ $alat->id }}">Form Pinjam: {{ $alat->nama_alat }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body row g-3">
                                <div class="col-12">
                                    <label class="form-label">Nama Lengkap Peminjam</label>
                                    <input type="text" name="nama_peminjam" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NIM / NIP</label>
                                    <input type="text" name="nim_nip" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prodi / Unit</label>
                                    <input type="text" name="prodi" class="form-control" placeholder="Contoh: D3 Keperawatan" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Jumlah Pinjam (Maks: {{ $alat->stok_tersedia }})</label>
                                    <input type="number" name="jumlah_pinjam" class="form-control" min="1" max="{{ $alat->stok_tersedia }}" value="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Pinjam</label>
                                    <input type="date" name="tgl_pinjam" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" name="tgl_kembali_rencana" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                Belum ada data alat laboratorium.
            </div>
        @endforelse
    </div>

    @if($alats->hasPages())
        <div class="mt-4">
            {{ $alats->links() }}
        </div>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Laporan Peminjaman Alat Lab</h2>
    <hr>

    <!-- Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3">
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
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tombol Cetak PDF -->
    <div class="mb-3">
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger">
            Cetak PDF
        </a>
    </div>

    <!-- Tabel Data Laporan -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>NIM/NIP</th>
                    <th>Prodi</th>
                    <th>Kode Alat</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $index => $item)
                    <tr>
                        <td class="text-center">{{ $peminjaman->firstItem() + $index }}</td>
                        <td>{{ $item->nama_peminjam }}</td>
                        <td>{{ $item->nim_nip }}</td>
                        <td>{{ $item->prodi }}</td>
                        <td class="text-center"><span class="badge bg-secondary">{{ $item->alat->kode_alat ?? '-' }}</span></td>
                        <td>{{ $item->alat->nama_alat ?? 'Alat Dihapus' }}</td>
                        <td class="text-center">{{ $item->jumlah_pinjam }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
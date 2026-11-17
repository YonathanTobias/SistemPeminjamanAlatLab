@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Hero Header -->
    <div class="p-4 p-md-5 mb-4 rounded-4 hero-banner-prodi text-white shadow-sm position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white text-dark mb-2 px-3 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-door-open-fill text-primary me-1"></i> Laboratorium & Ruang Praktikum
                </span>
                <h1 class="display-6 fw-bold mb-2">Peminjaman Ruang Laboratorium</h1>
                <p class="lead mb-0 opacity-90" style="font-size: 1rem;">
                    Ajukan peminjaman ruangan laboratorium untuk kegiatan perkuliahan, praktikum mandiri, responsi, atau penelitian secara online.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="#formBooking" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-calendar-plus me-1"></i> Form Pengajuan
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-xs" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-xs" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Daftar Ruangan Tersedia -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-building text-primary"></i> Daftar Ruangan Laboratorium
            </h4>
        </div>
        @forelse ($ruangans as $ruang)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill">
                                {{ $ruang->kode_ruangan }}
                            </span>
                            <span class="badge {{ $ruang->status === 'Tersedia' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-1">
                                {{ $ruang->status }}
                            </span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-2">{{ $ruang->nama_ruangan }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1 text-danger"></i> {{ $ruang->lokasi ?? 'Gedung Laboratorium' }}
                        </p>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-people me-1 text-primary"></i> Kapasitas: <strong>{{ $ruang->kapasitas }} Orang</strong>
                        </p>
                        <div class="bg-light p-3 rounded-3 mb-3 flex-grow-1">
                            <small class="text-muted fw-semibold d-block mb-1">Fasilitas:</small>
                            <small class="text-dark">{{ $ruang->fasilitas ?? 'Standar Laboratorium' }}</small>
                        </div>
                        <button type="button" class="btn btn-outline-primary w-100 rounded-pill btn-sm py-2 fw-semibold" onclick="pilihRuangan({{ $ruang->id }}, '{{ addslashes($ruang->nama_ruangan) }}')">
                            <i class="bi bi-calendar-check me-1"></i> Pilih Ruangan Ini
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-inbox text-muted display-4 mb-3"></i>
                    <h5 class="fw-bold">Belum Ada Data Ruangan</h5>
                    <p class="text-muted mb-0">Silakan hubungi laboran untuk informasi ketersediaan ruangan.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Form Pengajuan Booking Ruangan -->
    <div class="row justify-content-center" id="formBooking">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-pencil-square text-primary me-2"></i> Form Permohonan Peminjaman Ruang
                    </h4>
                    <p class="text-muted small">Isi data identitas dan jadwal penggunaan ruangan dengan lengkap.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('lab.ruangan.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Pilih Ruangan <span class="text-danger">*</span></label>
                                <select name="ruangan_id" id="selectRuangan" class="form-select rounded-3 @error('ruangan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Laboratorium / Ruangan --</option>
                                    @foreach ($ruangans as $ruang)
                                        <option value="{{ $ruang->id }}" {{ old('ruangan_id') == $ruang->id ? 'selected' : '' }} {{ $ruang->status !== 'Tersedia' ? 'disabled' : '' }}>
                                            {{ $ruang->nama_ruangan }} ({{ $ruang->kode_ruangan }}) - Kapasitas: {{ $ruang->kapasitas }} Orang {{ $ruang->status !== 'Tersedia' ? ' - '.$ruang->status : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruangan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap Pemohon <span class="text-danger">*</span></label>
                                <input type="text" name="nama_peminjam" class="form-control rounded-3 @error('nama_peminjam') is-invalid @enderror" value="{{ old('nama_peminjam') }}" placeholder="Nama Mahasiswa / Dosen" required>
                                @error('nama_peminjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIM / NIP <span class="text-danger">*</span></label>
                                <input type="text" name="nim_nip" class="form-control rounded-3 @error('nim_nip') is-invalid @enderror" value="{{ old('nim_nip') }}" placeholder="Nomor Induk Mahasiswa / Pegawai" required>
                                @error('nim_nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                                <input type="text" name="prodi" class="form-control rounded-3 @error('prodi') is-invalid @enderror" value="{{ old('prodi') }}" placeholder="Contoh: D3 Keperawatan / S1 Farmasi" required>
                                @error('prodi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Keperluan / Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="keperluan" class="form-control rounded-3 @error('keperluan') is-invalid @enderror" value="{{ old('keperluan') }}" placeholder="Contoh: Praktikum Mandiri / Ujian OSCE" required>
                                @error('keperluan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_pinjam" class="form-control rounded-3 @error('tgl_pinjam') is-invalid @enderror" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('tgl_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_mulai" class="form-control rounded-3 @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', '08:00') }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" class="form-control rounded-3 @error('jam_selesai', '10:00') }}" required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan / Keterangan Tambahan</label>
                                <textarea name="catatan" rows="3" class="form-control rounded-3" placeholder="Informasi tambahan kebutuhan peralatan atau perkiraan peserta...">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                    <i class="bi bi-send-check me-1"></i> Kirim Pengajuan Ruangan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function pilihRuangan(id, nama) {
    const select = document.getElementById('selectRuangan');
    if (select) {
        select.value = id;
        document.getElementById('formBooking').scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
@endsection

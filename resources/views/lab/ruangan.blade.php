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
                    Pilih ruangan laboratorium untuk kegiatan perkuliahan, praktikum mandiri, responsi, atau penelitian, lalu isi formulir pengajuan via popup modal.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <button type="button" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm fw-bold" onclick="bukaModalPinjamRuangan()">
                    <i class="bi bi-calendar-plus me-1"></i> Ajukan Peminjaman
                </button>
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-xs" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan pada isian formulir permohonan ruangan. Silakan periksa kembali.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Daftar Ruangan Tersedia -->
    <div class="row g-4 mb-5">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-building text-primary"></i> Daftar Ruangan Laboratorium
            </h4>
            <span class="text-muted small">Pilih salah satu ruangan untuk langsung membuka formulir pengajuan</span>
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
                        @if ($ruang->status === 'Tersedia')
                            <button type="button" class="btn btn-primary w-100 rounded-pill btn-sm py-2 fw-semibold shadow-sm" onclick="bukaModalPinjamRuangan({{ $ruang->id }}, '{{ addslashes($ruang->nama_ruangan) }} ({{ $ruang->kode_ruangan }}) - Kapasitas {{ $ruang->kapasitas }} Org')">
                                <i class="bi bi-calendar-check me-1"></i> Ajukan Pinjam Ruangan
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary w-100 rounded-pill btn-sm py-2 fw-semibold opacity-75" disabled>
                                <i class="bi bi-lock me-1"></i> Ruangan {{ $ruang->status }}
                            </button>
                        @endif
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
</div>

<!-- MODAL FORM PENGAJUAN PINJAM RUANGAN -->
<div class="modal fade" id="modalPinjamRuangan" tabindex="-1" aria-labelledby="modalPinjamRuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('lab.ruangan.store') }}" method="POST" id="formPengajuanRuangan">
                @csrf
                
                {{-- Anti-Bot Honeypot (Perangkap Bot Otomatis) --}}
                <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                    <input type="text" name="hp_field" tabindex="-1" autocomplete="off" value="">
                    <input type="hidden" name="form_loaded_at" value="{{ time() }}">
                </div>
                
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalPinjamRuanganLabel">
                            <i class="bi bi-calendar-check text-primary me-1"></i> Pengajuan Peminjaman Ruang Laboratorium
                        </h5>
                        <small class="text-muted">{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- Banner Info Ruangan Terpilih -->
                    <div id="selectedRuanganBanner" class="alert alert-info border-0 rounded-3 py-2 px-3 mb-4 d-flex align-items-center gap-2 small">
                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                        <div>
                            <span>Ruangan yang dipilih: <strong id="selectedRuanganNama">-</strong></span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Pilihan Ruangan -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-door-open text-primary"></i> Pilih Laboratorium / Ruangan <span class="text-danger">*</span>
                            </label>
                            <select name="ruangan_id" id="modalSelectRuangan" class="form-select @error('ruangan_id') is-invalid @enderror" required onchange="updateSelectedRuanganBanner()">
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

                        <!-- Biodata Pemohon -->
                        <div class="col-12 pt-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person-lines-fill text-primary me-1"></i> Biodata Pemohon:
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person text-primary"></i> Nama Lengkap Pemohon <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror" value="{{ old('nama_peminjam') }}" placeholder="Nama Mahasiswa / Dosen" required>
                            @error('nama_peminjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text text-primary"></i> NIM / NIP <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nim_nip" class="form-control @error('nim_nip') is-invalid @enderror" value="{{ old('nim_nip') }}" placeholder="Contoh: 202401001" required>
                            @error('nim_nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-mortarboard text-primary"></i> Program Studi / Tingkat <span class="text-danger">*</span>
                            </label>
                            <select name="prodi" class="form-select @error('prodi') is-invalid @enderror" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <option value="D3 Keperawatan" {{ old('prodi') === 'D3 Keperawatan' ? 'selected' : '' }}>D3 Keperawatan</option>
                                <option value="S1 Ilmu Keperawatan" {{ old('prodi') === 'S1 Ilmu Keperawatan' ? 'selected' : '' }}>S1 Ilmu Keperawatan</option>
                                <option value="Profesi Ners" {{ old('prodi') === 'Profesi Ners' ? 'selected' : '' }}>Profesi Ners</option>
                                <option value="D3 Rekam Medis & Info Kes" {{ old('prodi') === 'D3 Rekam Medis & Info Kes' ? 'selected' : '' }}>D3 Rekam Medis & Info Kes</option>
                                <option value="S1 Farmasi" {{ old('prodi') === 'S1 Farmasi' ? 'selected' : '' }}>S1 Farmasi</option>
                                <option value="Dosen / Instruktur Lab" {{ old('prodi') === 'Dosen / Instruktur Lab' ? 'selected' : '' }}>Dosen / Instruktur Lab</option>
                            </select>
                            @error('prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-medical text-primary"></i> Keperluan Praktikum / Kegiatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" value="{{ old('keperluan') }}" placeholder="Contoh: Praktikum Mandiri KMB, Responsi OSCE" required>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jadwal Peminjaman -->
                        <div class="col-12 pt-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-clock-history text-primary me-1"></i> Jadwal Penggunaan:
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event text-primary"></i> Tanggal Pinjam <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tgl_pinjam" class="form-control @error('tgl_pinjam') is-invalid @enderror" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            @error('tgl_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock text-primary"></i> Jam Mulai <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', '08:00') }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock-fill text-primary"></i> Jam Selesai <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', '10:00') }}" required>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text text-primary"></i> Catatan / Kebutuhan Tambahan (Opsional)
                            </label>
                            <textarea name="catatan" rows="3" class="form-control" placeholder="Tuliskan jika ada kebutuhan peralatan tambahan, perkiraan jumlah peserta, dsb...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                        <i class="bi bi-send-check me-1"></i> Kirim Pengajuan Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalPinjamRuanganInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('modalPinjamRuangan');
    if (modalEl && typeof bootstrap !== 'undefined') {
        modalPinjamRuanganInstance = new bootstrap.Modal(modalEl);
    }

    // Jika ada error validasi saat submit, otomatis buka kembali modalnya
    @if ($errors->any())
        if (modalPinjamRuanganInstance) {
            modalPinjamRuanganInstance.show();
        } else if (modalEl && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modalEl).show();
        }
    @endif

    updateSelectedRuanganBanner();
});

function bukaModalPinjamRuangan(ruanganId = null, ruanganNama = null) {
    const select = document.getElementById('modalSelectRuangan');
    if (ruanganId && select) {
        select.value = ruanganId;
    }

    updateSelectedRuanganBanner(ruanganNama);

    const modalEl = document.getElementById('modalPinjamRuangan');
    if (modalEl && typeof bootstrap !== 'undefined') {
        if (!modalPinjamRuanganInstance) {
            modalPinjamRuanganInstance = new bootstrap.Modal(modalEl);
        }
        modalPinjamRuanganInstance.show();
    }
}

function updateSelectedRuanganBanner(customText = null) {
    const select = document.getElementById('modalSelectRuangan');
    const banner = document.getElementById('selectedRuanganBanner');
    const label = document.getElementById('selectedRuanganNama');

    if (!banner || !label) return;

    if (customText) {
        label.textContent = customText;
        banner.classList.remove('d-none');
    } else if (select && select.selectedIndex > 0 && select.value) {
        label.textContent = select.options[select.selectedIndex].text;
        banner.classList.remove('d-none');
    } else {
        label.textContent = 'Belum dipilih (silakan pilih ruangan pada menu dropdown di bawah)';
    }
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Pengaturan Nama Sistem & Profil Laboratorium</h2>
        <p class="text-muted small mb-0">Sesuaikan nama sistem, program studi, unit laboratorium, dan informasi pengesahan dokumen sesuai kebutuhan prodi.</p>
    </div>
    <div>
        <a href="{{ route('lab.katalog') }}" target="_blank" class="btn btn-outline-primary shadow-xs">
            <i class="bi bi-box-arrow-up-right"></i> Lihat Tampilan Publik
        </a>
    </div>
</div>

@if((Auth::user()->role ?? '') === 'admin_it')
<!-- Quick Presets Box (Khusus Admin IT) -->
<div class="card border-0 rounded-4 shadow-sm mb-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-magic text-primary fs-5"></i>
                <h6 class="fw-bold text-dark mb-0">Template Cepat (Quick Preset Prodi):</h6>
            </div>
            <span class="badge bg-danger rounded-pill px-2 py-1 small">
                <i class="bi bi-shield-lock-fill me-1"></i> Khusus Admin IT
            </span>
        </div>
        <p class="text-muted small mb-3">Klik salah satu tombol di bawah untuk mengisi formulir secara otomatis sesuai program studi yang menggunakan sistem ini:</p>
        <div class="d-flex flex-wrap gap-2">
            <!-- 1. S1 Keperawatan & Profesi Ners: Orange -->
            <button type="button" class="btn btn-sm shadow-xs fw-semibold" style="background: #fff7ed; color: #c2410c; border: 1.5px solid #f97316;" onclick="applyPreset('s1_kep_ners')">
                <i class="bi bi-award-fill" style="color: #ea580c;"></i> S1 Keperawatan & Profesi Ners
            </button>

            <!-- 2. S1 Farmasi: Hijau -->
            <button type="button" class="btn btn-sm shadow-xs fw-semibold" style="background: #ecfdf5; color: #065f46; border: 1.5px solid #10b981;" onclick="applyPreset('s1_farmasi')">
                <i class="bi bi-capsule" style="color: #059669;"></i> S1 Farmasi
            </button>

            <!-- 3. D4 MIK: Ungu -->
            <button type="button" class="btn btn-sm shadow-xs fw-semibold" style="background: #faf5ff; color: #6b21a8; border: 1.5px solid #a855f7;" onclick="applyPreset('d4_mik')">
                <i class="bi bi-file-earmark-medical-fill" style="color: #9333ea;"></i> D4 MIK
            </button>
        </div>
    </div>
</div>
@endif

<!-- Main Settings Form -->
<form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- 1. Identitas Sistem & Prodi -->
        <div class="col-lg-7">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header-clean d-flex align-items-center gap-2">
                    <i class="bi bi-sliders2 text-primary fs-5"></i>
                    <span>Identitas Sistem & Program Studi</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-window-sidebar text-primary"></i> Nama Sistem / Aplikasi
                        </label>
                        <input type="text" id="inp_nama_sistem" name="nama_sistem" class="form-control" value="{{ old('nama_sistem', $pengaturan->nama_sistem ?? 'Sistem Peminjaman Alat Lab') }}" placeholder="Contoh: Sistem Peminjaman Alat Lab" required>
                        <div class="form-text small">Nama utama yang tampil pada navbar, title browser, dan header sistem.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-mortarboard text-primary"></i> Nama Program Studi
                        </label>
                        <input type="text" id="inp_nama_prodi" name="nama_prodi" class="form-control" value="{{ old('nama_prodi', $pengaturan->nama_prodi ?? 'Prodi S1 Keperawatan') }}" placeholder="Contoh: Prodi S1 Keperawatan" required>
                        <div class="form-text small">Nama program studi pemilik laboratorium ini.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-building text-primary"></i> Nama Institusi Kampus
                        </label>
                        <input type="text" id="inp_nama_institusi" name="nama_institusi" class="form-control" value="{{ old('nama_institusi', $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang') }}" placeholder="Contoh: STIKES Panti Waluya Malang" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-hospital text-primary"></i> Nama Unit Laboratorium
                        </label>
                        <input type="text" id="inp_unit_lab" name="unit_laboratorium" class="form-control" value="{{ old('unit_laboratorium', $pengaturan->unit_laboratorium ?? 'Unit Laboratorium Keperawatan & Kesehatan') }}" placeholder="Contoh: Unit Laboratorium Keperawatan" required>
                        <div class="form-text small">Tampil pada Kop Surat resmi laporan PDF dan footer.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Logo & Lokasi -->
        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header-clean d-flex align-items-center gap-2">
                    <i class="bi bi-image text-primary fs-5"></i>
                    <span>Logo & Tampilan Institusi</span>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="p-3 bg-light rounded-4 border mb-3 d-inline-block">
                        <img id="logoPreview" src="{{ asset($pengaturan->logo_path ?? 'images/logo-stikes.png') }}" alt="Logo Laboratorium" height="85" style="object-fit: contain;">
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold small text-dark">Ganti Logo Institusi (Opsional):</label>
                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*" onchange="previewLogo(this)">
                        <div class="form-text small">Format PNG/JPG/WebP, disarankan berlatar transparan (Maks. 2MB).</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Pejabat Pengesahan Dokumen PDF -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header-clean d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-person text-primary fs-5"></i>
                    <span>Pengesahan Dokumen & Laporan PDF</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-person-check text-primary"></i> Nama Kepala Unit Laboratorium
                        </label>
                        <input type="text" id="inp_kepala_lab" name="kepala_lab" class="form-control" value="{{ old('kepala_lab', $pengaturan->kepala_lab ?? 'Ns. Wening Prabawati, M.Kep.') }}" placeholder="Nama beserta gelar akademik" required>
                        <div class="form-text small">Nama yang tercetak pada kolom tanda tangan laporan PDF.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-card-heading text-primary"></i> NIP / NIK Kepala Lab
                        </label>
                        <input type="text" id="inp_nip_kepala_lab" name="nip_kepala_lab" class="form-control" value="{{ old('nip_kepala_lab', $pengaturan->nip_kepala_lab ?? '198205142010122001') }}" placeholder="Nomor Induk Pegawai">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Kontak & Lokasi Institusi -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header-clean d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt text-primary fs-5"></i>
                    <span>Kontak & Lokasi Laboratorium</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-pin-map text-primary"></i> Alamat Lengkap Kampus / Laboratorium
                        </label>
                        <textarea id="inp_alamat" name="alamat_institusi" class="form-control" rows="2" required>{{ old('alamat_institusi', $pengaturan->alamat_institusi ?? 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117') }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">
                            <i class="bi bi-telephone text-primary"></i> No. Telepon / Kontak Lab
                        </label>
                        <input type="text" id="inp_kontak" name="kontak_lab" class="form-control" value="{{ old('kontak_lab', $pengaturan->kontak_lab ?? '(0341) 369003') }}" placeholder="(0341) 369003">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button Row -->
        <div class="col-12 text-end mb-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-check2-circle"></i> Simpan Seluruh Pengaturan
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/settings.js') }}"></script>
@endpush

@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('admin.alat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
                <i class="bi bi-arrow-left"></i> Inventaris Alat
            </a>
            <span class="text-muted small">/ Master Data</span>
        </div>
        <h2 class="fw-bold text-dark mb-1">Master Kategori Praktikum</h2>
        <p class="text-muted small mb-0">Kelola kelompok kategori peralatan lab, ikon visual, dan deskripsi cakupan praktikum mahasiswa.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            <i class="bi bi-plus-circle-fill"></i> Tambah Kategori Baru
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 p-3 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: var(--primary-light, #e0f2fe);">
                <i class="bi bi-grid-3x3-gap-fill fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Kategori Praktikum</div>
                <div class="fs-4 fw-bold text-dark">{{ $kategoris->count() }} Kategori</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 p-3 text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ecfdf5;">
                <i class="bi bi-boxes fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Alat Terkelompok</div>
                <div class="fs-4 fw-bold text-dark">{{ $kategoris->sum('alats_count') }} Jenis Alat</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 p-3 text-info d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #f0f9ff;">
                <i class="bi bi-tags-fill fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Status Integrasi</div>
                <div class="fs-4 fw-bold text-success">Aktif & Sinkron</div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-list-stars text-primary fs-5"></i>
            <span class="fw-bold">Daftar Master Kategori Praktikum</span>
        </div>
        <span class="badge badge-soft-primary px-3 py-2 rounded-pill small">
            {{ $kategoris->count() }} Terdaftar
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 60px;">No</th>
                    <th style="min-width: 220px;">Nama Kategori & Ikon</th>
                    <th style="min-width: 280px;">Deskripsi Cakupan</th>
                    <th class="text-center" style="width: 140px;">Jumlah Alat</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $index => $kat)
                    <tr>
                        <td class="ps-4 text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center shadow-xs text-primary" style="width: 42px; height: 42px; background: var(--primary-light, #e0f2fe); font-size: 1.25rem;">
                                    <i class="bi {{ $kat->ikon ?: 'bi-grid-fill' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $kat->nama_kategori }}</div>
                                    <code class="text-muted small">{{ $kat->ikon ?: 'bi-grid-fill' }}</code>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $kat->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}</span>
                        </td>
                        <td class="text-center">
                            @if($kat->alats_count > 0)
                                <a href="{{ route('admin.alat.index', ['kategori' => $kat->nama_kategori]) }}" class="badge badge-soft-primary px-3 py-2 rounded-pill text-decoration-none fw-bold" title="Lihat daftar alat kategori ini">
                                    <i class="bi bi-box-seam me-1"></i> {{ $kat->alats_count }} Alat
                                </a>
                            @else
                                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill small">0 Alat</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-circle p-2" title="Edit Kategori" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $kat->id }}" style="width: 34px; height: 34px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                @if($kat->alats_count == 0)
                                    <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $kat->nama_kategori }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Hapus Kategori" style="width: 34px; height: 34px;">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-2 opacity-50" title="Kategori sedang digunakan oleh {{ $kat->alats_count }} alat, tidak dapat dihapus" disabled style="width: 34px; height: 34px;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                            <h6 class="fw-bold">Belum Ada Kategori Praktikum</h6>
                            <p class="small mb-3">Klik tombol "Tambah Kategori Baru" untuk mendaftarkan kategori alat pertama Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH KATEGORI BARU -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header hero-banner-prodi text-white">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori Praktikum
                        </h5>
                        <small class="text-white-50">Daftarkan kategori alat baru untuk katalog dan inventaris</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-tag text-primary"></i> Nama Kategori Praktikum
                        </label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Farmakologi & Uji Lab" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-palette text-primary"></i> Ikon Bootstrap (Class Icon)
                        </label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light text-primary" id="iconPreviewAdd"><i class="bi bi-grid-fill"></i></span>
                            <input type="text" id="iconInputAdd" name="ikon" class="form-control" placeholder="bi-capsule / bi-heart-pulse-fill" value="bi-grid-fill" onkeyup="document.querySelector('#iconPreviewAdd i').className = 'bi ' + this.value;">
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-light text-dark border cursor-pointer small" onclick="document.getElementById('iconInputAdd').value='bi-capsule'; document.querySelector('#iconPreviewAdd i').className='bi bi-capsule';"><i class="bi bi-capsule me-1"></i> Obat/Kapsul</span>
                            <span class="badge bg-light text-dark border cursor-pointer small" onclick="document.getElementById('iconInputAdd').value='bi-heart-pulse-fill'; document.querySelector('#iconPreviewAdd i').className='bi bi-heart-pulse-fill';"><i class="bi bi-heart-pulse-fill me-1"></i> Jantung/KDM</span>
                            <span class="badge bg-light text-dark border cursor-pointer small" onclick="document.getElementById('iconInputAdd').value='bi-person-arms-up'; document.querySelector('#iconPreviewAdd i').className='bi bi-person-arms-up';"><i class="bi bi-person-arms-up me-1"></i> Manikin</span>
                            <span class="badge bg-light text-dark border cursor-pointer small" onclick="document.getElementById('iconInputAdd').value='bi-lightning-charge-fill'; document.querySelector('#iconPreviewAdd i').className='bi bi-lightning-charge-fill';"><i class="bi bi-lightning-charge-fill me-1"></i> Elektromedis</span>
                            <span class="badge bg-light text-dark border cursor-pointer small" onclick="document.getElementById('iconInputAdd').value='bi-scissors'; document.querySelector('#iconPreviewAdd i').className='bi bi-scissors';"><i class="bi bi-scissors me-1"></i> Bedah</span>
                            <span class="badge bg-light text-dark border cursor-pointer small" onclick="document.getElementById('iconInputAdd').value='bi-file-earmark-medical-fill'; document.querySelector('#iconPreviewAdd i').className='bi bi-file-earmark-medical-fill';"><i class="bi bi-file-earmark-medical-fill me-1"></i> Rekam Medis</span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-card-text text-primary"></i> Deskripsi Cakupan Kategori (Opsional)
                        </label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan jenis peralatan yang termasuk dalam kategori ini..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT KATEGORI (Per Item) -->
@foreach($kategoris as $kat)
    <div class="modal fade" id="modalEditKategori{{ $kat->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <form action="{{ route('admin.kategori.update', $kat->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header hero-banner-prodi text-white">
                        <div>
                            <h5 class="modal-title fw-bold mb-0">
                                <i class="bi bi-pencil-square me-1"></i> Edit Kategori Praktikum
                            </h5>
                            <small class="text-white-50">Perbarui rincian kategori dan sinkronkan dengan alat terkait</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-tag text-primary"></i> Nama Kategori Praktikum
                            </label>
                            <input type="text" name="nama_kategori" class="form-control" value="{{ $kat->nama_kategori }}" required>
                            @if($kat->alats_count > 0)
                                <div class="form-text text-warning small">
                                    <i class="bi bi-info-circle-fill"></i> Mengubah nama ini akan otomatis memperbarui {{ $kat->alats_count }} peralatan lab yang menggunakan kategori ini.
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-palette text-primary"></i> Ikon Bootstrap (Class Icon)
                            </label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light text-primary" id="iconPreviewEdit{{ $kat->id }}"><i class="bi {{ $kat->ikon ?: 'bi-grid-fill' }}"></i></span>
                                <input type="text" id="iconInputEdit{{ $kat->id }}" name="ikon" class="form-control" value="{{ $kat->ikon ?: 'bi-grid-fill' }}" onkeyup="document.querySelector('#iconPreviewEdit{{ $kat->id }} i').className = 'bi ' + this.value;">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-card-text text-primary"></i> Deskripsi Cakupan Kategori
                            </label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $kat->deskripsi }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

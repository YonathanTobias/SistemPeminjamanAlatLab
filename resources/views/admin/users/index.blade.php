@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Manajemen Akun Pengguna & Hak Akses</h2>
        <p class="text-muted small mb-0">Kelola data akun pengguna, role hak akses, dan kredensial login ke sistem laboratorium.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="bi bi-person-plus-fill"></i> + Tambah Pengguna Baru
        </button>
    </div>
</div>

<!-- Stat KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="text-muted small fw-medium">Total Akun Terdaftar</span>
                <h4 class="fw-bold mb-0 text-dark">{{ $stats['total'] ?? 0 }} User</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="text-muted small fw-medium">Administrator IT</span>
                <h4 class="fw-bold mb-0 text-dark">{{ $stats['admin_it'] ?? 0 }} Akun</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <span class="text-muted small fw-medium">Petugas Laboran</span>
                <h4 class="fw-bold mb-0 text-dark">{{ $stats['laboran'] ?? 0 }} Akun</h4>
            </div>
        </div>
    </div>
</div>

<!-- Filter Box -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau email pengguna..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select form-select-sm">
                    <option value="">-- Semua Hak Akses / Role --</option>
                    <option value="admin_it" {{ request('role') == 'admin_it' ? 'selected' : '' }}>🛡️ Admin IT</option>
                    <option value="laboran" {{ request('role') == 'laboran' ? 'selected' : '' }}>🔬 Petugas Laboran</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Users Table Card -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
    <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-lines-fill text-primary fs-5"></i>
            <span>Daftar Pengguna Sistem</span>
        </div>
        <span class="badge badge-soft-secondary">{{ $users->total() }} Pengguna</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th class="text-center" style="width: 140px;">Hak Akses (Role)</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-center pe-4" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $u)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="{{ $u->role === 'admin_it' ? 'bg-danger' : 'bg-primary' }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 38px; height: 38px; font-weight: 700;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            {{ $u->name }}
                                            @if(auth()->id() == $u->id)
                                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 small" style="font-size: 0.65rem;">(Akun Anda)</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">ID User: #{{ $u->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium text-dark">{{ $u->email }}</span>
                            </td>
                            <td class="text-center">
                                @if($u->role === 'admin_it')
                                    <span class="badge badge-soft-danger px-3 py-2">
                                        <i class="bi bi-shield-check"></i> Admin IT
                                    </span>
                                @else
                                    <span class="badge badge-soft-primary px-3 py-2">
                                        <i class="bi bi-person-badge"></i> Laboran
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted">{{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }} WIB</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    @if(auth()->id() != $u->id)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $u->name }} ({{ $u->email }})?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-xs">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block text-muted opacity-50 mb-2"></i>
                                Tidak ada data pengguna yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center border-top">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- MODAL TAMBAH USER BARU (Diluar Tabel) -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
                        </h5>
                        <small class="text-white-50">Daftarkan akun petugas laboran atau admin IT baru</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-person text-primary"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Ns. Budi Santoso, S.Kep." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-envelope text-primary"></i> Alamat Email (Untuk Login)
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="nama@stikes.ac.id" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-shield-check text-primary"></i> Hak Akses (Role)
                        </label>
                        <select name="role" class="form-select" required>
                            <option value="laboran">🔬 Petugas Laboran (Kelola Alat & Peminjaman)</option>
                            <option value="admin_it">🛡️ Admin IT (Super Admin - Akses Penuh)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">
                            <i class="bi bi-key text-primary"></i> Kata Sandi (Password)
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan & Daftarkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT USER PER PENGGUNA (Diluar Tabel) -->
@foreach($users as $u)
    <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0f766e 100%);">
                        <div>
                            <h5 class="modal-title fw-bold mb-0">
                                <i class="bi bi-pencil-square me-1"></i> Edit Akun [{{ $u->name }}]
                            </h5>
                            <small class="text-white-50">Perbarui identitas, role, atau reset kata sandi</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-person text-primary"></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $u->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-envelope text-primary"></i> Alamat Email
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $u->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                <i class="bi bi-shield-check text-primary"></i> Hak Akses (Role)
                            </label>
                            <select name="role" class="form-select" required>
                                <option value="laboran" {{ $u->role === 'laboran' ? 'selected' : '' }}>🔬 Petugas Laboran (Kelola Alat & Peminjaman)</option>
                                <option value="admin_it" {{ $u->role === 'admin_it' ? 'selected' : '' }}>🛡️ Admin IT (Super Admin - Akses Penuh)</option>
                            </select>
                        </div>

                        <div class="mb-0 p-3 bg-light rounded-3 border">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-key text-primary"></i> Reset Kata Sandi (Opsional)
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika sandi tidak diubah" minlength="6">
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Isi hanya jika ingin mengganti kata sandi pengguna ini (minimal 6 karakter).</small>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

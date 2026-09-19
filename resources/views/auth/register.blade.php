@extends('layouts.app')

@section('content')
<div class="auth-split-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card auth-split-card border-0 bg-white">
                    <div class="row g-0">
                        <!-- Left Side: Institutional Info -->
                        <div class="col-lg-6 auth-brand-side">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                        <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-white mb-0">STIKES Panti Waluya</h5>
                                        <small class="text-white-50">Portal Registrasi Laboran Baru</small>
                                    </div>
                                </div>

                                <h3 class="fw-bold text-white mb-3">Registrasi Petugas Laboratorium</h3>
                                <p class="text-white-50 small mb-4">
                                    Halaman pendaftaran khusus staf & laboran untuk akses manajemen inventaris dan verifikasi transaksi peminjaman alat praktikum.
                                </p>
                            </div>

                            <div class="pt-3 border-top border-white border-opacity-10 text-white-50 small">
                                &copy; {{ date('Y') }} Laboratorium STIKES Panti Waluya Malang
                            </div>
                        </div>

                        <!-- Right Side: Form -->
                        <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                            <div class="mb-4">
                                <h4 class="fw-bold text-dark mb-1">Daftar Akun Baru</h4>
                                <p class="text-muted small">Lengkapi formulir untuk membuat akun staf laboran.</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label small fw-semibold text-muted">
                                        <i class="bi bi-person text-primary"></i> Nama Lengkap
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                        <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nama Petugas Laboran" required autocomplete="name" autofocus>
                                    </div>
                                    @error('name')
                                        <span class="text-danger small mt-1 d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-semibold text-muted">
                                        <i class="bi bi-envelope text-primary"></i> Alamat Email Kampus
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="bi bi-at"></i>
                                        </span>
                                        <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="laboran@stikespantiwaluya.ac.id" required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <span class="text-danger small mt-1 d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row g-2 mb-4">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label small fw-semibold text-muted">
                                            <i class="bi bi-lock text-primary"></i> Sandi
                                        </label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="new-password">
                                        @error('password')
                                            <span class="text-danger small mt-1 d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password-confirm" class="form-label small fw-semibold text-muted">
                                            <i class="bi bi-shield-check text-primary"></i> Ulangi Sandi
                                        </label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Daftarkan Akun Laboran</span>
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-2">
                                <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                                    <i class="bi bi-arrow-left"></i> Sudah punya akun? Masuk di sini
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

